<?php

namespace app\modules\secretariat_v2\controllers;

use app\models\Intldate;
use app\models\Constant;
use app\modules\secretariat\models\EceSecretariat;
use app\modules\secretariat\models\Secretariat;
use app\modules\secretariat\models\SecretariatAttachment;
use app\modules\secretariat\models\SecretariatPhoto;
use Ddeboer\Imap\Server;
use Tx\Mailer;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\MessageHandler;
use app\modules\reseller\models\Reseller;
use app\modules\secretariat\models\SecretariatPeople;
use app\modules\secretariat\models\SecretariatPeopleSearch;
use app\models\CodeGenerator;
use app\modules\manage\models\User;
use yii\web\UploadedFile;

class MailController extends Controller
{
    public $layout = '@app/views/layouts/ManageMain';

    

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('SecretariatMailIndexPerm');
                        }
                    ],
                    [
                        'actions' => ['send-recept'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('SecretariatMailIndexPerm');
                        }
                    ],
                    [
                        'actions' => ['send-ece'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('SecretariatMailIndexPerm');
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {

        $server = new Server(
            'mail.bahar.network',
            '143',
            '/novalidate-cert'
        );
        $connection = $server->authenticate('ece@baharnet.ir', 'Bahar42576000');
        $mailboxe = $connection->getMailbox('INBOX');
        $messages = $mailboxe->getMessages();

        foreach ($messages as $message) {
//            var_dump($message->getHeaders()->get('fromaddress'));
//            die();
            $full_address = [];
            if ($message->isDeleted()) {
                continue;
            }

            $filename = date('Y-m-d-H-i-s') . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999));
            $attaches = $message->getAttachments();
            foreach ($attaches as $attach) {
                file_put_contents(
                    'uploads/ece_file/' . $filename . $attach->getFilename(),
                    $attach->getDecodedContent()
                );
                chmod("uploads/ece_file/" . $filename . $attach->getFilename(), 0777);
                $path_info = pathinfo("uploads/ece_file/" . $filename . $attach->getFilename());

                if ($path_info['extension'] == 'xml' || $path_info['extension'] == 'XML') {
//                         $this->Read("uploads/ece_file/".$filename. $attach->getFilename());
                    $id = $this->Read("uploads/ece_file/" . $filename . $attach->getFilename());

                    if ($id == 0) {
                        echo "Can Not Store" . " uploads/ece_file/" . $filename . $attach->getFilename();
                        die();
                    }
                }
            }
            $message->delete();
        }

//        $connection->expunge();

        $connection->close();
        MessageHandler::ShowMessage('اطلاعات با موفقیت دریافت شد', 'success');
        return $this->redirect('/secretariatv2/secretariat/list-letters');
    }

    private function Read($xml_address = null)
    {
//        secretariatHide
//        secretariat_attachment
//        secretariat_ece_detail
        $xml = simplexml_load_file($xml_address);
        $result = [];
        foreach ($xml->Sender->attributes() as $key => $val) {

            $result['sender'][(string)$key] = (string)$val;
        }
        $ece_detail = new EceSecretariat();
        $ece_detail->path = $xml_address;
        $ece_detail->sender_organization = isset($result['sender']['Organization']) ? $result['sender']['Organization'] : '';
        $ece_detail->sender_department = isset($result['sender']['Department']) ? $result['sender']['Department'] : '';
        $ece_detail->sender_position = isset($result['sender']['Position']) ? $result['sender']['Position'] : '';
        $ece_detail->sender_name = isset($result['sender']['Name']) ? $result['sender']['Name'] : '';
        $ece_detail->sender_Code = isset($result['sender']['Code']) ? $result['sender']['Code'] : '';
        foreach ($xml->Receiver->attributes() as $key => $val) {
            $result['receiver'][(string)$key] = (string)$val;
        }
        $ece_detail->receiver_organization = isset($result['receiver']['Organization']) ? $result['receiver']['Organization'] : '';
        $ece_detail->receiver_department = isset($result['receiver']['Department']) ? $result['receiver']['Department'] : '';
        $ece_detail->receiver_position = isset($result['receiver']['Position']) ? $result['receiver']['Position'] : '';
        $ece_detail->receiver_name = isset($result['receiver']['Name']) ? $result['receiver']['Name'] : '';
        $ece_detail->receiver_Code = isset($result['receiver']['Code']) ? $result['receiver']['Code'] : '';
        $ece_detail->save();
        $ece_detail_id = $ece_detail->id;


        $result['secretariat_number'] = (string)$xml->LetterNo;
        $result['title'] = (string)$xml->Subject;
        $result['date'] = strtotime((string)$xml->LetterDateTime);
        foreach ($xml->Priority->attributes() as $key => $val) {
            if ($key != "Name") {
                continue;
            }
            $result['priority'] = (string)$val;
        }
        foreach ($xml->Classification->attributes() as $key => $val) {
            if ($key != "Name") {
                continue;
            }
            $result['access_level'] = (string)$val;
        }

        // setting default properties
        $generator = new CodeGenerator();
        $result['type'] = Constant::SECRETARIAT_INPUT_TYPE;
        $result['archive_number'] = $generator->generateCode('input_letter');
        $result['description'] = Constant::ECE;
        $result['status'] = Constant::ACCEPT;
        $result['category'] = Constant::SECRETARIAT_CATEGORY_NEW;
        $result['created_at'] = time();
        $result['ece_detail_id'] = $ece_detail_id;

        $secretariat = new Secretariat();
        $secretariat->scenario = Secretariat::SCENARIO_ECE;

        $secretariat->attributes = $result;

        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $secretariat->link('creator', User::findOne(2258));
            $secretariat->link('reseller', Reseller::findOne(1));
            $secretariat->save(false);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            return 0;
        }

        $id = $secretariat->id;


        for ($i=0; $i <count($xml->Origins->Origin); $i++)
        {
            foreach($xml->Origins->Origin[$i]->attributes() as $key => $val)
            {
                $filename = date('Y-m-d-H-i-s') . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999));
                if($key == "Extension")
                {
                    $data = $xml->Origins->Origin[$i];
                    $data = base64_decode($data);
                    $file = 'uploads/ece_file/'.$filename. '.'.$val;
                    $success = file_put_contents($file, $data);
                    chmod($file, 0777);
                    $secretariat_file = new SecretariatPhoto();
                    $secretariat_file->secretariat_id = $id;
                    $secretariat_file->path = $file;
                    $secretariat_file->created_at = time();
                    if (!$secretariat_file->save()) {
                        echo $secretariat_file->errors;
                        die();
                    }
                }

            }
        }

        for ($i=0; $i <count($xml->Attachments->Attachment); $i++)
        {
            foreach($xml->Attachments->Attachment[$i]->attributes() as $key => $val)
            {
                $filename = date('Y-m-d-H-i-s') . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999));
                if($key == "Extension")
                {
                    $data = $xml->Attachments->Attachment[$i];
                    $data = base64_decode($data);
                    $file = 'uploads/ece_file/'.$filename. '.'.$val;
                    $success = file_put_contents($file, $data);
                    chmod($file, 0777);
                    $secretariat_file = new SecretariatAttachment();
                    $secretariat_file->secretariat_id = $id;
                    $secretariat_file->path = $file;
                    $secretariat_file->created_at = time();
                    if (!$secretariat_file->save()) {
                        echo $secretariat_file->errors;
                        die();
                    }
                }

            }
        }
        return $id;
    }

    public function actionSendRecept()
    {
        $doc = new \DOMDocument('1.0','UTF-8');
// we want a nice output
        $doc->formatOutput = true;
        $root = $doc->createElement('Letter');
        $root = $doc->appendChild($root);
//protocol
        $title = $doc->createElement('Protocol');

        $attrName = $doc->createAttribute('Name');
        $attrName->value = 'ECE';
        $title->appendChild($attrName);

        $attrName = $doc->createAttribute('Version');
        $attrName->value = '1.01';
        $title->appendChild($attrName);

        $title = $root->appendChild($title);

//softwere
        $title = $doc->createElement('Software');

        $attrName = $doc->createAttribute('SoftwareDeveloper');
        $attrName->value = 'مهدی بهاری';
        $title->appendChild($attrName);


        $attrName = $doc->createAttribute('Version');
        $attrName->value = '1.0.0';
        $title->appendChild($attrName);


        $attrName = $doc->createAttribute('GUID');
        $attrName->value = '3c094873-a2ef-41d9-9027-a567d1e18e7e';

        $title->appendChild($attrName);

        $title = $root->appendChild($title);

//LetterNo tamam
        $title = $doc->createElement('LetterNo');
        $title = $root->appendChild($title);

        $text = $doc->createTextNode("secretariat_number454544");
        $title = $title->appendChild($text);




//Letter DateTime
        $title = $doc->createElement('LetterDateTime');
        $title = $root->appendChild($title);

        $attrName = $doc->createAttribute('ShowAs');
        $attrName->value = 'jalali';
        $title->appendChild($attrName);

        $text = $doc->createTextNode(date('Y-m-d\TH:i:s+04:30'));
        $title = $title->appendChild($text);


//LetterReceiver
        $title = $doc->createElement('LetterReceiver');
        $title = $root->appendChild($title);

        $attrName = $doc->createAttribute('Organization');
        $attrName->value = 'بهارسامانه';
        $title->appendChild($attrName);

        $attrName = $doc->createAttribute('Department');
        $attrName->value = 'نرم افزار';
        $title->appendChild($attrName);

        $attrName = $doc->createAttribute('Position');
        $attrName->value = '';
        $title->appendChild($attrName);

        $attrName = $doc->createAttribute('Name');
        $attrName->value = '';
        $title->appendChild($attrName);

        $attrName = $doc->createAttribute('Code');
        $attrName->value = 'f91ba4af-2030-40ad-8f54-742dea3ab569;;ece@baharnet.ir';
        $title->appendChild($attrName);




//tamam
        $title = $doc->createElement('SentCode');
        $title = $root->appendChild($title);


        $text = $doc->createTextNode("TransId;;819827;;ece@baharnet.ir");
        $title = $title->appendChild($text);


//tamam
        $title = $doc->createElement('RegistrationNo');
        $title = $root->appendChild($title);


        $text = $doc->createTextNode("baygani 45454");
        $title = $title->appendChild($text);


        $title = $doc->createElement('RegistrationDateTime');
        $title = $root->appendChild($title);

        $attrName = $doc->createAttribute('ShowAs');
        $attrName->value = 'jalali';
        $title->appendChild($attrName);
        $text = $doc->createTextNode(date('Y-m-d\TH:i:s+04:30'));
        $title = $title->appendChild($text);















    }

    public function actionSendEce()
    {
        $ece_detail = new EceSecretariat();


        if(Yii::$app->request->post())
        {
        $doc = new \DOMDocument('1.0','UTF-8');
        $doc->formatOutput = true;
        $root = $doc->createElement('Letter');
        $root = $doc->appendChild($root);

        $title = $doc->createElement('Protocol');
        $attrName = $doc->createAttribute('Name');
        $attrName->value = 'ECE';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('Version');
        $attrName->value = '1.01';
        $title->appendChild($attrName);

        $title = $doc->createElement('Software');
        $attrName = $doc->createAttribute('SoftwareDeveloper');
        $attrName->value = 'بهارسامانه';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('Version');
        $attrName->value = '1.0.0';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('GUID');
        $attrName->value = '3c094873-a2ef-41d9-9027-a567d1e18e7e';
        $title->appendChild($attrName);
        $title = $root->appendChild($title);

        $title = $doc->createElement('Sender');
        $attrName = $doc->createAttribute('Department');
        $attrName->value = 'نرم افزار';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('Code');
        $attrName->value = 'f91ba4af-2030-40ad-8f54-742dea3ab569;;ece@baharnet.ir';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('Position');
        $attrName->value = 'مدیر نرم افزار';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('Organization');
        $attrName->value = 'بهارسامانه';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('Name');
        $attrName->value = 'شرکت بهار سامانه شرق';
        $title->appendChild($attrName);
        $title = $root->appendChild($title);

        $title = $doc->createElement('Receiver');
        $attrName = $doc->createAttribute('Department');
        $attrName->value = 'اتوماسیون اداری';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('Code');
        $attrName->value = 'id;;78974;;ece@baharnet.ir';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('Position');
        $attrName->value = 'کارشناس';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('Organization');
        $attrName->value = 'شرکت تنظیم مقررات';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('Name');
        $attrName->value = 'خانم مهندس دولت شاهی';
        $title->appendChild($attrName);
        $title = $root->appendChild($title);

        $title = $doc->createElement('LetterNo');
        $title = $root->appendChild($title);
        $text = $doc->createTextNode("114/25");
        $title = $title->appendChild($text);

        $title = $doc->createElement('LetterDateTime');
        $title = $root->appendChild($title);
        $attrName = $doc->createAttribute('ShowAs');
        $attrName->value = 'jalali';
        $title->appendChild($attrName);
        $text = $doc->createTextNode(date('Y-m-d\TH:i:s+04:30'));
        $title = $title->appendChild($text);

        $title = $doc->createElement('Subject');
        $title = $root->appendChild($title);
        $text = $doc->createTextNode("تست");
        $title = $title->appendChild($text);

        $title = $doc->createElement('Priority');
        $attrName = $doc->createAttribute('Code');
        $attrName->value = '41';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('Name');
        $attrName->value = 'عادی';
        $title->appendChild($attrName);
        $title = $root->appendChild($title);

        $title = $doc->createElement('Classification');
        $attrName = $doc->createAttribute('Code');
        $attrName->value = '1';
        $title->appendChild($attrName);
        $attrName = $doc->createAttribute('Name');
        $attrName->value = 'عادی';
        $title->appendChild($attrName);
        $title = $root->appendChild($title);

        $orgins = $doc->createElement('Origins');
        $orgins = $root->appendChild($orgins);
        $title1 = $doc->createElement('Origin');
        $title1 = $orgins->appendChild($title1);
        $attrName = $doc->createAttribute('ContentType');
        $attrName->value = 'application/jpeg';
        $title1->appendChild($attrName);
        $attrName = $doc->createAttribute('Extension');
        $attrName->value = 'jpg';
        $title1->appendChild($attrName);
        $attrName = $doc->createAttribute('LetterBody');
        $attrName->value = 'Letter';
        $title1->appendChild($attrName);





//        $orgins = $doc->createElement('Attachments');
//        $orgins = $root->appendChild($orgins);
//        $title1 = $doc->createElement('Attachment');
//        $title1 = $orgins->appendChild($title1);
//        $attrName = $doc->createAttribute('ContentType');
//        $attrName->value = 'application/jpeg';
//        $title1->appendChild($attrName);
//        $attrName = $doc->createAttribute('Extension');
//        $attrName->value = 'jpg';
//        $title1->appendChild($attrName);
//        $attrName = $doc->createAttribute('LetterBody');
//        $attrName->value = 'Letter';
//        $title1->appendChild($attrName);
//
//        $text = $doc->createTextNode("base64 img");
//        $title1 = $title1->appendChild($text);

        $ece_detail_photo = new SecretariatPhoto();
        $ece_detail = new EceSecretariat();
        $ece_detail_photo->secretariat_id=24;
        $ece_detail_photo->created_at=time();


        $ece_detail->origin = UploadedFile::getInstances($ece_detail, 'origin');
        if ( $ece_detail->origin != null) {
            if ($ece_detail->validate()) {
                foreach ($ece_detail->origin as $file) {
                    $file_path_attachment = 'uploads/ece_file/' . date('Y-m-d-H-i-s') . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999)) .'.'.$file->extension;
                    $file->saveAs($file_path_attachment);
                    $ece_detail_photo->path = $file_path_attachment;
                    $ece_detail_photo->save();
                    $origin = file_get_contents($file_path_attachment);
                    $encode_data = base64_encode($origin);
                    $text = $doc->createTextNode($encode_data);
                    $title1 = $title1->appendChild($text);
                }
            } else {
                MessageHandler::ShowMessage('خطا در ذخیره سازی پیوست رخ داده است.', 'error');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }


        $data = $doc->saveXML();
        $filename = date('Y-m-d-H-i-s') . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999));
        $file = 'uploads/ece_file/'.$filename. '.XML';
        $success = file_put_contents($file, $data);
        chmod($file, 0777);
        die();



        $ok = (new Mailer())
            ->setServer('mail.bahar.network', 25)
            ->setAuth('ece@baharnet.ir', 'Bahar42576000')
            ->setFrom('BaharSamaneh', 'ece@baharnet.ir')
            ->addTo('ece', 'ece@baharnet.ir')
            ->addAttachment($filename, $file)
            ->send();
        }

//        $ece_detail = new EceSecretariat();
//        $ece_detail->path = $file;
//        $ece_detail->sender_organization = isset($result['sender']['Organization']) ? $result['sender']['Organization'] : '';
//        $ece_detail->sender_department = isset($result['sender']['Department']) ? $result['sender']['Department'] : '';
//        $ece_detail->sender_position = isset($result['sender']['Position']) ? $result['sender']['Position'] : '';
//        $ece_detail->sender_name = isset($result['sender']['Name']) ? $result['sender']['Name'] : '';
//        $ece_detail->sender_Code = isset($result['sender']['Code']) ? $result['sender']['Code'] : '';
//        $ece_detail->receiver_organization = isset($result['receiver']['Organization']) ? $result['receiver']['Organization'] : '';
//        $ece_detail->receiver_department = isset($result['receiver']['Department']) ? $result['receiver']['Department'] : '';
//        $ece_detail->receiver_position = isset($result['receiver']['Position']) ? $result['receiver']['Position'] : '';
//        $ece_detail->receiver_name = isset($result['receiver']['Name']) ? $result['receiver']['Name'] : '';
//        $ece_detail->receiver_Code = isset($result['receiver']['Code']) ? $result['receiver']['Code'] : '';
//        $ece_detail->save();
//

        return $this->render('Example',['ece_detail' => $ece_detail]);





    }
}
