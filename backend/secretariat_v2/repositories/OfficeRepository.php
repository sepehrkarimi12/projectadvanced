<?php
namespace app\modules\secretariat_v2\repositories;

use app\models\CodeGenerator;
use app\models\Intldate;
use app\modules\manage\models\User;
use app\modules\secretariat_v2\models\OfficeAssign;
use app\modules\secretariat_v2\models\OfficeAttachment;
use app\modules\secretariat_v2\models\OfficeFolder;
use app\modules\secretariat_v2\models\OfficeMain;
use app\modules\secretariat_v2\models\OfficePeople;
use app\modules\secretariat_v2\models\OfficePhoto;
use app\modules\secretariat_v2\models\OfficeRelation;
use app\modules\secretariat_v2\models\OfficeTranscript;
use app\modules\secretariat_v2\Services\ArchiveLetter;
use app\modules\secretariat_v2\Services\BeginTransaction;
use app\modules\secretariat_v2\Services\ChainOfResponsibilities;
use app\modules\secretariat_v2\Services\ChangeCategory;
use app\modules\secretariat_v2\Services\CheckAssignPermission;
use app\modules\secretariat_v2\Services\CheckAvailability;
use app\modules\secretariat_v2\Services\ConfirmationLetter;
use app\modules\secretariat_v2\Services\DeleteAttachment;
use app\modules\secretariat_v2\Services\deleteCategory;
use app\modules\secretariat_v2\Services\DeleteModel;
use app\modules\secretariat_v2\Services\DeleteOfficeFolder;
use app\modules\secretariat_v2\Services\FillInputFields;
use app\modules\secretariat_v2\Services\FillOutputFields;
use app\modules\secretariat_v2\Services\FillPriorityFields;
use app\modules\secretariat_v2\Services\FillTranscripts;
use app\modules\secretariat_v2\Services\Request;
use app\modules\secretariat_v2\Services\SaveAttachmentUploads;
use app\modules\secretariat_v2\Services\SaveFolders;
use app\modules\secretariat_v2\Services\SaveModel;
use app\modules\secretariat_v2\Services\SaveOfficeFolders;
use app\modules\secretariat_v2\Services\SaveUploads;
use app\modules\secretariat_v2\Services\SetAssigns;
use app\modules\secretariat_v2\Services\SetIsSeen;
use kartik\mpdf\Pdf;
use Yii;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

class OfficeRepository
{
    /**
     * Returns name of class.
     *
     * @return string
     */
    public static function className()
    {
        return get_called_class();
    }

    /**
     * @param $id : The office's id
     * @param $action : the name of operation
     * @return boolean
     * @throws \Exception
     * @author Noei
     */
    public static function deleteLetter($id)
    {
        // checking for users permission
        if (!Yii::$app->user->can('SecretariatDeleteLetterPerm')) {
            throw new ForbiddenHttpException();
        }
        $secretariat = OfficeMain::findOne($id);

        if ($secretariat->delete()){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id : The office's id
     * @return boolean
     * @throws \Exception
     * @author Noei
     */
    public static function archiveLetter($id)
    {
        // checking for users permission
        if (!Yii::$app->user->can('SecretariatArchiveLetterPerm')) {
            throw new ForbiddenHttpException();
        }
        $secretariat = OfficeMain::findOne($id);

        if ($secretariat->archive()){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $office_id
     * @return array
     * @author Mehran
     */
    public static function letterAssign($office_id)
    {
        $assign = OfficeAssign::find()
            ->where(['office_id' => $office_id])
            ->andWhere(['user_id' => Yii::$app->user->identity->id])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        $assign_logs_query = OfficeAssign::find()
            ->where(['office_id' => $office_id])
            ->all();

        $assign_logs = [];
        foreach ($assign_logs_query as $key => $assign_log) {
            $assign_log->created_at = Intldate::get()->timestampToPersian($assign_log->created_at);
            $assign_logs[] = $assign_log;
        }

        $assign_tree = [];

        if ($assign != null) {
            $assign_tree['childs'] = [];
            foreach ($assign->childNodes as $childNode) {
                if ($childNode->office_id == $office_id) {
                    $childNode->created_at = Intldate::get()->timestampToPersian($childNode->created_at);
                    $assign_tree['childs'][] = $childNode;
                }
            }

            // set assigns array
            $assign->created_at = Intldate::get()->timestampToPersian($assign->created_at);
            $assign_tree['assigns'][] = $assign;

            while ($assign->parentNode != null) {
                $assign = $assign->parentNode;
                $assign->created_at = Intldate::get()->timestampToPersian($assign->created_at);
                $assign_tree['assigns'][] = $assign;
            }

            $assign_tree['assigns'] = array_reverse($assign_tree['assigns']);
            // end assigns array
        }

        $assignment = array(
            'assign_tree' => $assign_tree,
            'assign_logs' => $assign_logs,
        );

        $assignment = array(
            'assign_tree' => null,
            'assign_logs' => null,
        );

        return $assignment;
    }

    /**
     * @param $office_id
     * @return null|string
     * @author Mehran
     */
    public static function letterCategory($office_id)
    {
        $result = null;

        $office_main = OfficeMain::findOne($office_id);
        if($office_main->category == null) {
            $result = 'جدید';
        } else {
            if ($office_main->category->name != 'جدید' && $office_main->category->name != null && $relation_id = $office_main->officeRelations->office_relation_id != null) {
                $result = $office_main->category->name . '  به نامه ' . $office_main->archive_number;
            } else {
                $result = $office_main->category->name;
            }
        }

        return $result;
    }

    public static function fetchOfficeFolder($office_id, $folder_id)
    {
        $office_folder = OfficeFolder::findOne([
            'office_id' => $office_id,
            'folder_id' => $folder_id,
        ]);

        if ($office_folder != null) {
            return $office_folder;
        } else {
            throw new \yii\base\Exception('OfficeFolder not found');
        }
    }

    /**
     * This method switches between input and output letters and fills its database
     * @param $office
     * @return bool
     * @author Noei
     * @throws Exception
     */
    public static function addOffice($office)
    {
        switch ($office->office_type_id) {
            case 1:
                $chain = new ChainOfResponsibilities();
                $chain->setSuccessor(new BeginTransaction())
                    ->setSuccessor(new FillInputFields())
                    ->setSuccessor(new FillPriorityFields())
                    ->setSuccessor(new SaveFolders())
                    ->setSuccessor(new SaveUploads())
                    ->setSuccessor(new SetAssigns())
                    ->setSuccessor(new SaveAttachmentUploads());
                $chain->handleRequest(new Request($office));
                break;
            case 2:
                $chain = new ChainOfResponsibilities();
                $chain->setSuccessor(new BeginTransaction())
                    ->setSuccessor(new FillOutputFields())
                    ->setSuccessor(new SaveFolders())
                    ->setSuccessor(new SaveUploads())
                    ->setSuccessor(new SetAssigns())
                    ->setSuccessor(new SaveAttachmentUploads());
                $chain->handleRequest(new Request($office));
                break;
            default:
                throw new Exception('invalid type id');
        }
        return true;
    }


    /**
     * This will delete the given model
     * @param $model
     * @return bool
     * @author Noei
     */
    public static function deleteModel($model)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new DeleteModel());
            $chain->handleRequest(new Request($model));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * This will
     * @param $folder
     * @return bool
     * @author Noei
     */
    public static function deleteOfficeFolder($folder)
    {

        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new DeleteModel());
            $chain->handleRequest(new Request($folder));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $office
     * @return bool
     * @author Noei
     */
    public static function saveOfficeFolders($office)
    {

        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new SaveOfficeFolders());
            $chain->handleRequest(new Request($office));

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param $office
     * @return bool
     * @author Mehran
     */
    public static function confirmationLetter($office)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new CheckAvailability())->setSuccessor(new ConfirmationLetter());
            $chain->handleRequest(new Request($office));

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * This method used for download letter in PDF format
     * @param model $office
     * @return bool|mixed
     * @authoe Mehran
     */
    public static function downloadLetter($office)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new CheckAvailability());
            $chain->handleRequest(new Request($office));

            $office_output = $office->officeOutputs;
            $content = $office_output->content;

            // setup kartik\mpdf\Pdf component
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                // A4 paper format
                'format' => Pdf::FORMAT_A4,
                // portrait orientation
                'orientation' => Pdf::ORIENT_PORTRAIT,
                // stream to browser inline
                'destination' => Pdf::DEST_BROWSER,
                // your html content input
                'content' => $content,
                // format content from your own css file if needed or use the
                // enhanced bootstrap css built by Krajee for mPDF formatting
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                // any css to be embedded if required
                'cssInline' => '.kv-heading-1{font-size:18px}',
                // set mPDF properties on the fly
                'options' => ['title' => 'Krajee Report Title'],
            ]);

            /**
             * return the pdf output as per the destination setting
             */
            return $pdf->render();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * this method for check model is exist or not from officeMain table
     * @param model $office
     * @return bool
     */
    public static function checkAvailability($office)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new CheckAvailability());
            $chain->handleRequest(new Request($office));

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * This method use in RenderLastLetter action in SecretariatController for convert each letter to output letter
     * @param model $office
     * @return bool
     * @author Mehran
     */
    public static function getRenderInformation($office)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new CheckAvailability());
            $chain->handleRequest(new Request($office));
            /**
             * Find the sender of letter
             * The sender letter means that person who receive letter
             * if letter has no receiver the CEO of Company is who person recieve letter
             */
            if ($office->officeOutputs == null) {
                /**
                 * Find the manager of Bahar Samaneh
                 * The CEO peopleid is 2
                 */
                $people_id = OfficeMain::managePeople();

                $manage = OfficePeople::findOne($people_id);
                $letter_info['sender'] = $manage->name . ' ' . $manage->family;
                $letter_info['sender_id'] = 2;
            } else {
                $letter_info['sender'] = $office->officeOutputs->receiver->name . ' ' . $office->officeOutputs->receiver->family;
                $letter_info['sender_id'] = $office->officeOutputs->receiver->id;
            }

            /**
             * This section for choose reviver of letter
             */
            $letter_info['receiver'] = $office->sender->name . ' ' . $office->sender->family;
            $letter_info['receiver_id'] = $office->sender->id;

            /**
             * This section for find created_at date and use it in letter
             */
            $date = Intldate::get()->timestampToPersian($office->created_at);
            $date = substr($date, 0, 10);
            $letter_info['date'] = $date;

            $letter_info['number'] = $office->archive_number;
            $letter_info['title'] = $office->title;
            $letter_info['occupation'] = $office->sender->occuaption_name . ' ' . $office->sender->unit_name . ' ' . $office->sender->company_name;

            if ($letter_info == null) {
                Throw new Exception("The Letter Render Information not Found!");
            }

            return $letter_info;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param $content
     * @param $date
     * @param $number
     * @param $receiver
     * @param $occupation
     * @param $title
     * @param $sender
     * @return string
     * @author Mehran
     */
    public static function renderConvertLetterContent($content, $date, $number, $receiver, $occupation, $title, $sender)
    {
        $html = '<table style="width:100%;direction: rtl">
                        <tr>
                            <td style="width: 20%;">                                
                                <img src="asset_admin/custom/img/logoc.png" style="width: 70px; height: 70px; margin-right: 10px;" />
                                <p style="margin-right: 20px; margin-top: 20px;"> بهار سامانه شرق</p>
                            </td>
                            <td style="width: 55%; text-align: center">
                                <h4 style="font-weight: bold; font-family:Tahoma "> به نام خدا </h4>
                            </td>
                            <td style="width: 25%; padding-top: 50px">
                                <p style="text-align: left; font-family: Tahoma">  تاربخ : ' . $date . '  </p>
                                <p style="text-align: left">  شماره نامه : ' . $number . '  </p>
                            </td>
                        </tr>
                    </table>
        
                    <div style="width: 100%; margin-right: 20px;" >
                        <div style="padding: 30px 20px 0px 20px; text-align: right; width: 100%">
                            <p style="font-weight: bold; font-size: 12pt; margin-bottom: 10px;"> دریافت کننده :   جناب ' . $receiver  .'</p>
                            <p style="font-weight: bold; font-size: 12pt;">   ' . $occupation .  '</p>
                            <br/>
                            <br/>
                            <p style="font-weight: bold; font-size: 14pt; margin-bottom: 10px; text-align: right"> عنوان :  ' . $title  .'</p>
       
                        </div>
                    </div>
                    <div style="width: 100%">
                        <div style="padding: 10px 50px 0px 0px; text-align: right; width: 100%">
                            <p style=" font-size: 10pt;"> با سلام و احترام </p>
        
                        </div>
                    </div>';
        $html .= '<div style="padding: 10px 50px 0px 0px; text-align: right;">' . $content . '</div>';
        $html .= '<div style="width: 100%; text-align: left; padding-left: 100px; padding-top: 10px">
                            <div id="signeture"></div>
                            <h4 style="font-weight: bold; font-size: 12pt; margin-top: -20px; padding-left: 90px">با تشکر </h4>
                            <h4 style="font-weight: bold; font-size: 12pt; padding-left: 50px">  ' . $sender . ' </h4>
                       </div>
                       <hr/>
                       <div class="row">
                           <div class="col-sm-12 text-left" style="direction: rtl;">
                               <div style="padding:10px; margin: 20px 0px">
                                   <table style="float: left; font-size: 12px;">
                                       <tr><td colspan="3"><span style="font-weight: bold">آدرس:</span> تهران، خیابان شیراز، کوچه ژاله، پلاک 6</td></tr>
                                       <tr>
                                           <td><span style="font-weight: bold">کد پستی:</span> 53381-14369</td>
                                           <td><span style="font-weight: bold;">تلفن:</span> 42576000-021</td>
                                           <td><span style="font-weight: bold;">پست الکترونیکی:</span> info@bahar.network</td>
                                       </tr>
                                       <tr><td colspan="3">شرکت بهار سامانه شرق دارای مجوز servco به شماره پروانه 1009548 از سازمان تنظیم مقررات رادیویی</td></tr>
                                   </table>
                               </div>
                           </div>
                       </div>';

        return $html;
    }

    /**
     * @param $office_main
     * @param $office_output
     * @param $letter_info
     * @return bool
     * @throws \yii\db\Exception
     * @author mehran
     */
    public static function convertLetterToOutput($office_main, $office_output, $letter_info)
    {
        $generator = new CodeGenerator();

        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            $office_main->assigned_person = OfficeMain::assignPerson();

            if ($office_output->content == null) {
                Throw new Exception("The content of letter is Required!");
            }

            $actionator_id = null;
            $new_office = new OfficeMain();
            $new_office->archive_prefix = null;
            $new_office->archive_number = $generator->generateCode('output_letter');;
            $new_office->date = time();
            $new_office->title = $office_main->title;
            $new_office->description = $office_main->description;
            $new_office->status_id = 1;
            $new_office->is_archived = 0;
            $new_office->date = time();
            $new_office->sender_id = $letter_info['sender_id'];
            //office_type_id == 2 ===> Output Letter
            $new_office->office_type_id = 2;
            $new_office->access_level_id =  $office_main->access_level_id;
            $new_office->priority_id =  $office_main->priority_id;
            $new_office->category_id =  $office_main->category_id;
            $new_office->letter_date =  $office_main->letter_date;
            $new_office->save();

            $content = self::renderConvertLetterContent($office_output->content, $new_office->date, $new_office->archive_number, $letter_info['receiver'], $letter_info['occupation'], $letter_info['title'], $letter_info['sender']);

            $office_output->office_id = $new_office->id;
            $office_output->is_old = 0;
            $office_output->is_signed = 0;
            $office_output->content = $content;
            $office_output->actionator_id = null;
            $office_output->receiver_id = $office_main->sender_id;
            $office_output->save();

            $office_assign = new OfficeAssign();
            $office_assign->link('user', User::findOne($office_main->assigned_person));
            $office_assign->link('office', $office_main);
            $office_assign->depth = 0;
            $office_assign->save();

            $transaction->commit();
            return true;

        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * @param $office
     * @return bool
     * @author Mehran
     */
    public static function viewLetter($office)
    {
        try {
            // Responsibilities of this action workflow
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new CheckAvailability())->setSuccessor(new CheckAssignPermission())->setSuccessor(new SetIsSeen());
            $chain->handleRequest(new Request($office));

            return true;
        } catch (Exception $e) {
            return false;
        }

    }

    /**
     * @param integer $office_id
     * @return OfficeTranscript[]|null
     * @author Mehran
     */
    public static function letterTranscripts($office_id, $request_id = false)
    {
        $transcripts = null;

        $transcripts = OfficeTranscript::find()
            ->Where(['office_id' => $office_id])
            ->all();
        if ($transcripts != null) {
            if ($request_id) {
                foreach ($transcripts as $key => $value) {
                    $data[] = $value->officePeople->id;
                }
            } else {
                foreach ($transcripts as $key => $value) {
                    $data[] = $value->officePeople->name . ' ' . $value->officePeople->family . ' ' . $value->officePeople->occuaption_name . ' ' . $value->officePeople->unit_name . ' ' . $value->officePeople->company_name;
                }
            }
            $transcripts = $data;
        } else {
            $transcripts = null;
        }

        return $transcripts;
    }

    /**
     * @param integer $office_id
     * @return null|string
     * @author Mehran
     */
    public static function findRelationNumber($office)
    {
        $relation_number = null;

        if ($office->category->name != 'جدید' && $relation_id = $office->officeRelations->office_relation_id != null )
        {
            if (isset($office->officeRelations) && $office->officeRelations != null) {
                if ($relation_id = $office->officeRelations->office_relation_id != null )
                {
                    $relation_number = $office->archive_number;
                }
            }
        }
        return $relation_number;
    }

    /**
     * @param $office
     * @return boolean
     * @throws \Exception
     * @author Mehran
     */
    public static function CheckLetterAssign($office)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new CheckAvailability())->setSuccessor(new CheckAssignPermission());
            $chain->handleRequest(new Request($office));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * This method handles all operations on update letter
    * @param $office : office model
    * @return bool
    * @throws \yii\db\Exception
    * @author Noei
    */
    public static function updateLetter($office)
    {
        switch ($office->office_type_id) {
            case 1:
                $chain = new ChainOfResponsibilities();
                $chain->setSuccessor(new BeginTransaction())
                    ->setSuccessor(new FillInputFields())
                    ->setSuccessor(new SaveUploads())
                    ->setSuccessor(new SaveAttachmentUploads());
                $chain->handleRequest(new Request($office));
                break;
            case 2:
                $chain = new ChainOfResponsibilities();
                $chain->setSuccessor(new BeginTransaction())
                    ->setSuccessor(new FillTranscripts())
                    ->setSuccessor(new FillOutputFields())
                    ->setSuccessor(new SaveUploads())
                    ->setSuccessor(new SaveAttachmentUploads());
                $chain->handleRequest(new Request($office));
                break;
            default:
                throw new Exception('invalid type id');
        }
        return true;
    }

    /**
     * @param $office_main
     * @param $office_upload
     * @param $office_output
     * @return bool
     * @throws \yii\db\Exception
     * @author Mehran
     */
    public static function outputLetter($office_main, $office_upload, $office_output)
    {
        $office_id = $office_main->id;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();

        /**
         * Save photo Or Save LetterContent
         */
        $office_upload->photo = UploadedFile::getInstances($office_upload, 'photo');
        if ($office_upload->photo != null) {
            try {
                $office_output = $office_main->officeOutputs;
                foreach ($office_upload->photo as $photo) {
                    $file_path = 'uploads/output_secretariat_photos/' . date('Y-m-d-H-i-s') . "_" . $office_id . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999)) . '.' . $photo->extension;
                    $office_photo = new OfficePhoto();
                    $office_photo->office_id = $office_id;
                    $office_photo->path = $file_path;
                    $office_photo->save();
                    $photo->saveAs($file_path);
                }
                $office_main->status = 1;
                $office_main->save();

                $office_output->is_signed = 1;
                $office_output->save();

                $transaction->commit();

                return true;
            } catch (Exception $e) {
                $transaction->rollBack();

                return false;
            }

        } elseif ($office_output->content != null) {
            try {
                $html = self::OutputletterContent($office_main->archive_number, $office_main->receiver_id, $office_output->content);
                /**
                 * Save Letter in secretariat Table
                 */
                $office_output->content = $html;
                $office_output->save();

                $office_main->status_id = 1;
                $office_main->save();

                $transaction->commit();

                return true;
            } catch (Exception $e) {
                $transaction->rollBack();

                return false;
            }

        } else {
            return false;
        }
    }

    /**
     * @param $archive_number
     * @param $receiver_id
     * @param $content
     * @return string
     * @author Mehran
     */
    public static function OutputletterContent($archive_number, $receiver_id, $content)
    {
        /**
         * In This Section Add Part For Get Content From CKEditor
         */
        $number = $archive_number;
        $date = Intldate::get()->timestampToPersian(time(),'yyyy/MM/dd');
        $receiver_user = OfficePeople::findOne($receiver_id);

        $position = null;
        $receiver = null;
        $office = null;
        if ($receiver_user != null) {
            $receiver = $receiver_user->name . " " . $receiver_user->family;
            $position = $receiver_user->occuaption_name;
            $position .= " محترم ";
            $office = $receiver_user->company_name;
        }

        $html = '<table style="width:100%;direction: rtl">
                <tr>
                    <td style="width: 20%;">
                        <img src="/asset_admin/custom/img/logoc.png" style="width: 70px; height: 70px; margin-right: 38px;" />
                        <div style="margin-right: 20px;">بهار سامانه شرق</div>
                    </td>
                    <td style="width: 60%; text-align: center">
                        <h4 style="font-weight: bold"> به نام خدا </h4>
                    </td>
                    <td style="width: 20%; padding-top: 50px">
                        <div style="text-align: left"> تاریخ : ' . $date .' </div>
                        <div style="text-align: left"> شماره نامه : ' . $number .' </div>
                    </td>
                </tr>
            </table>
            <div style="padding: 30px 20px 0px 20px; text-align: right; width: 100%">
                <div style="font-weight: bold; font-size: 12pt; margin-bottom: 10px;"> ' . $receiver .'  </div>
                <div style="font-weight: bold; font-size: 12pt;"> ' . $position . $office .'  </div>
            </div>
            <br/>
            <div style="padding: 10px 20px; text-align: right">'. $content . ' </div>
            <div style="padding: 10px 20px;">
                <div style="font-weight: bold; font-size: 12pt; text-align: right;">باتشکر</div>
            </div>
            <footer style="width: 100%;margin-top:10px; text-align: left; direction: rtl;">
               <div style="padding:10px; margin: 20px 0px">
                    <table style="float: left; font-size: 12px;">
                        <tr><td colspan="3"><span style="font-weight: bold">آدرس:</span> تهران، خیابان شیراز، کوچه ژاله، پلاک 6</td></tr>
                        <tr>
                            <td><span style="font-weight: bold">کد پستی:</span> 53381-14369</td>
                            <td><span style="font-weight: bold;">تلفن:</span> 42576000-021</td>
                            <td><span style="font-weight: bold;">پست الکترونیکی:</span> info@bahar.network</td>
                        </tr>
                        <tr><td colspan="3">شرکت بهار سامانه شرق دارای مجوز servco به شماره پروانه 1009548 از سازمان تنظیم مقررات رادیویی</td></tr>
                    </table>
               </div>
            </footer>';

        return $html;
    }

    /**
     * @param $releation_id
     * @return array|\yii\db\ActiveRecord|null
     * @author Mehran
     */
    public static function treeRelation($releation_id)
    {
        try {
            $relation = OfficeRelation::findOne(['office_id' => $releation_id]);
            if ($relation == null) {
                $relation = OfficeRelation::find()->where(['office_relation_id' => $releation_id])->one();
            }

            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new CheckAvailability());
            $chain->handleRequest(new Request($relation));

            $relation_tree = OfficeRelation::find()
                ->where(['family_id' => $relation->family->id])
                ->orderBy(['office_relation_id' => SORT_ASC])
                ->one();
            $relation_tree->officeRelation->date = isset($relation_tree->officeRelation->date) ? Intldate::get()->timestampToPersian($relation_tree->officeRelation->date) : "";
            return $relation_tree;

        } catch (Exception $e) {
            return null;
        }
    }

    public static function changeCategory($office)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new CheckAvailability())->setSuccessor(new deleteCategory())->setSuccessor(new ChangeCategory())->setSuccessor(new SaveModel());
            $chain->handleRequest(new Request($office));

            return true;
        } catch(Exception $exception) {
            return false;
        }
    }
}
