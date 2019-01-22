<?php

namespace app\modules\secretariat_v2\controllers;

use app\models\Intldate;
use app\models\MessageHandler;
use app\models\CodeGenerator;
use app\modules\manage\models\User;
use app\modules\secretariat_v2\models\OfficeAssign;
use app\modules\secretariat_v2\models\OfficeAttachment;
use app\modules\secretariat_v2\models\OfficeFolderType;
use app\modules\secretariat_v2\models\OfficeDeadline;
use app\modules\secretariat_v2\models\OfficeInput;
use app\modules\secretariat_v2\models\OfficeOutput;
use app\modules\secretariat_v2\models\OfficePhoto;
use app\modules\secretariat_v2\models\OfficeRelation;
use app\modules\secretariat_v2\models\OfficeRelationType;
use app\modules\secretariat_v2\models\OfficeTranscript;
use app\modules\secretariat_v2\models\uploads\OfficeUpload;
use app\modules\secretariat_v2\models\OfficeMain;
use app\modules\secretariat_v2\models\OfficeFolder;
use app\modules\secretariat_v2\models\OfficeTools;
use app\modules\secretariat_v2\models\searchModels\OfficeLettersSearch;
use app\modules\secretariat_v2\models\uploads\OfficeUploadAttachment;
use app\modules\secretariat_v2\repositories\CommonRepository;
use app\modules\secretariat_v2\repositories\OfficeRepository;
use app\modules\secretariat_v2\Services\BeginTransaction;
use app\modules\secretariat_v2\Services\ChainOfResponsibilities;
use app\modules\secretariat_v2\Services\FillInputFields;
use app\modules\secretariat_v2\Services\FillInputOrOutputs;
use app\modules\secretariat_v2\Services\FillOutputFields;
use app\modules\secretariat_v2\Services\Request;
use app\modules\secretariat_v2\Services\SaveAttachmentUploads;
use app\modules\secretariat_v2\Services\SaveFolders;
use app\modules\secretariat_v2\Services\SaveUploads;
use app\modules\secretariat_v2\Services\SetAssigns;
use app\modules\secretariat_v2\Services\SetInputFields;
use app\modules\secretariat_v2\Services\SwitchBtwInputOrOutputs;
use app\modules\secretariat_v2\Services\SwitchInType;
use Exception;
use Symfony\Component\DomCrawler\Field\InputFormField;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;
use app\models\Bulldog;

class SecretariatController extends Controller
{
    public $layout = '@app/views/layouts/ManageMain';
    public $office_tools;

    const DELETE = 'delete';
    const UPDATE = 'update';
    const ARCHIVE = 'archive';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['add-letter'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2AddLetterPerm');
//                        }
                    ],
                    [
                        'actions' => ['list-letters'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2ListLettersPerm');
//                        }
                    ],
                    [
                        'actions' => ['view-letter'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2ViewletterPerm');
//                        }
                    ],
                    [
                        'actions' => ['update-letter'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2UpdateletterPerm');
//                        }
                    ],
                    [
                        'actions' => ['update-letter-folder'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2UpdateLetterFolderPerm');
//                        }
                    ],
                    [
                        'actions' => ['delete-letter-folder'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2DeleteLetterFolderPerm');
//                        }
                    ],
                    [
                        'actions' => ['list-private-letters'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2ListPrivateLettersPerm');
//                        }
                    ],
                    [
                        'actions' => ['show-output-letter-code'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2AddLetterPerm');
//                        }
                    ],
                    [
                        'actions' => ['archive-letter'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2ArchiveLetterPerm');
//                        }
                    ],
                    [
                        'actions' => ['confirmation-letter'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2ConfirmationLetterPerm');
//                        }
                    ],
                    [
                        'actions' => ['download-letter'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2ConfirmationLetterPerm');
//                        }
                    ],
                    [
                        'actions' => ['render-last-letter'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2RenderlastLetterPerm');
//                        }
                    ],
                    [
                        'actions' => ['tree-relation'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2TreeRelationPerm');
//                        }
                    ],
                    [
                        'actions' => ['change-letter-category'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2ChangeLetterCategoryPerm');
//                        }
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

    public function init()
    {
        $this->office_tools = new OfficeTools();
    }


    /**
     * This action is responsible for adding both input and output letters
     * @return string
     * @throws \yii\base\Exception
     * @author Noei
     */
    public function actionAddLetter()
    {
        $office = new OfficeMain();

        if ($office->load(Yii::$app->request->post()) && $office->validate()) {
            try {
                OfficeRepository::addOffice($office);
            } catch (Exception $exception) {
                Yii::$app->print->ShowMessage('خطا در ورودی پارامترها', 'error', [$this, 'add-letter']);
            }
            Yii::$app->print->ShowMessage('با موفقیت ثبت شد', 'success', [$this, 'add-letter']);
        }

        return $this->render('AddLetter', [
            'office_main' => $office,
            'current_date' => Intldate::get()->fetchDate('Y/M/D'),
        ]);
    }

    /**
     * This action is responsible for showing list of letters and operates delete letters
     * @param $id : office id
     * @param $action : actions to perform on list delete,
     * @return string
     * @throws Exception
     * @author Noei
     */
    public function actionListLetters($all = 0, $id = null, $action = null)
    {
        // this scope is for performing delete, operations
        if ($id != null) {
            switch ($action) {
                case self::DELETE:
                    if (OfficeRepository::deleteLetter($id)) {
                        Yii::$app->print->ShowMessage('باموفقیت حذف شد', 'error', [$this, 'list-letters']);
                    }
                    break;
                case self::ARCHIVE:
                    if (OfficeRepository::archiveLetter($id)) {
                        Yii::$app->print->ShowMessage('باموفقیت آرشیو شد', 'error', [$this, 'list-letters']);
                    }
                default:
            }
        }


        $searchModel = Yii::createObject(OfficeLettersSearch::className());
        if ($all != 1) {
            $all = 0;
        }
//        pd($all);
        $searchModel->all = $all;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('ListLetters', [
            'officeLettersdataProvider' => $dataProvider,
            'officeLettersSearch' => $searchModel,
        ]);
    }

    /**
     * This action is responsible showing the letter
     * @param $id : office id
     * @return string
     * @throws Exception
     * @author Noei
     */
    public function actionViewLetter($id = null)
    {
        $office_main = OfficeMain::findOne($id);

        OfficeRepository::viewLetter($office_main);

        return $this->render('ViewLetter', [
            'office_main' => $office_main,
            'assigns' => OfficeRepository::letterAssign($office_main->id),
            'transcripts' => OfficeRepository::letterTranscripts($office_main->id),
            'category' => OfficeRepository::letterCategory($office_main->id)
        ]);
    }

    /**
     * This action is responsible for updating a letter
     * @param $id : office id
     * @param $action : actions to perform on list delete attachment
     * @param $attachment_id : office attachment id
     * @return string
     * @throws Exception
     * @author Noei
     */
    public function actionUpdateLetter($id, $action = null, $attachment_id = null)
    {
        $office = OfficeMain::findOne($id);

        // check if the user has the permission to access the letter
        if (!OfficeRepository::CheckLetterAssign($office)) {
            throw new ForbiddenHttpException();
        }

        // scope for performing url operations
        if ($attachment_id != null) {
            switch ($action) {
                case self::DELETE:
                    try {
                        OfficeRepository::deleteModel(OfficeAttachment::findOne($attachment_id));
                    } catch (Exception $exception) {
                        MessageHandler::ShowMessage('مشکلی در حذف اطلاعات وجود دارد!', 'error');
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                    MessageHandler::ShowMessage('با موفقیت حذف شد', 'success');
                    return $this->redirect(Yii::$app->request->referrer);
                    break;
                default:
            }
        }

        // load posted data and validate it for performing update operation
        if ($office->load(Yii::$app->request->post()) && $office->validate()) {
            try {
                MessageHandler::ShowMessage('نامه با موفقیت ویرایش شد.', 'success');
                return $this->redirect(Yii::$app->request->referrer);
            } catch (Exception $exception) {
                MessageHandler::ShowMessage('خطا در خیره سازی', 'error');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('UpdateLetter', [
            'office_main' => $office,
            'attachments' => isset($office->officeAttachments) ? $office->officeAttachments : null,
        ]);
    }

    /**
     * This action will delete a letters folder
     * the usage is passing the folder id via url
     * @param $folder_id : office folder id
     * @return string
     * @throws Exception
     * @author Noei
     */
    public function actionDeleteLetterFolder($folder_id)
    {
        if (OfficeRepository::deleteModel(OfficeFolder::findOne($folder_id))) {
            MessageHandler::ShowMessage('با موفقیت حذف شد', 'success');
            return $this->redirect(Yii::$app->request->referrer);
        }
        MessageHandler::ShowMessage("خطا در ذخیره سازی اطلاعات", "error");
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionShowOutputLetterCode($office_id)
    {
        $office_upload = new OfficeUpload();

        $office_main = OfficeMain::findOne($office_id);

        if (!CommonRepository::checkAvailability($office_main)) {
            MessageHandler::ShowMessage('نامه یافت نشد.', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        Bulldog::checkAccess([
            'reseller_id' => $office_main->reseller_id,
            'on' => ['reseller'],
        ]);

        $office_output = $office_main->officeOutputs;
        if ($office_main->load(Yii::$app->request->post()) && $office_output->load(Yii::$app->request->post()) && $office_main->validate()) {
            $output_letter = OfficeRepository::outputLetter($office_main, $office_upload, $office_output);
            if ($output_letter) {
                MessageHandler::ShowMessage('تصاویر با موفقیت افزوده شد.', 'success');
                return $this->redirect('/secretariat_v2/secretariat/list-letters');
            }
            MessageHandler::ShowMessage('خطایی رخ داده است.', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('ShowOutputLetterCode', [
            'office_main' => $office_main,
            'secretariat_upload' => $office_upload,
            'secretariat_transcripts' => OfficeRepository::letterTranscripts($office_main->id),
            'receiver' => isset($office_main->officeOutputs->receiver) ? $office_main->officeOutputs->receiver->name . ' ' . $office_main->officeOutputs->receiver->family . ' ' . $office_main->officeOutputs->receiver->occuaption_name . ' ' . $office_main->officeOutputs->receiver->unit_name . ' ' . $office_main->officeOutputs->receiver->company_name : null,
            'sender' => isset($office_main->sender) ? $office_main->sender->name . ' ' . $office_main->sender->family . ' ' . $office_main->sender->occuaption_name . ' ' . $office_main->sender->unit_name . ' ' . $office_main->sender->company_name : null,
            'actionator' => isset($office_main->officeOutputs->actionator) ? $office_main->officeOutputs->actionator->name . ' ' . $office_main->officeOutputs->actionator->family . ' ' . $office_main->officeOutputs->actionator->occuaption_name . ' ' . $office_main->officeOutputs->actionator->unit_name . ' ' . $office_main->officeOutputs->actionator->company_name : null,
            'office_output' => $office_output
        ]);
    }

    /**
     * @param integer $id
     * @return string
     * @throws Exception
     * @author Noei, Mehran
     */
    public function actionTreeRelation($id)
    {
        try {
            $relation_tree = OfficeRepository::treeRelation($id);
        } catch (Exception $exception) {
            MessageHandler::ShowMessage('خطایی رخ داده است.', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->render('TreeRelation', [
            'relation_tree' => $relation_tree,
        ]);
    }

    /**
     * @param $office_id, $delete
     * @return string
     * @author Noei, Mehran
     * @throws $mysqli_sql_exception
     */
    public function actionUpdateLetterFolder($office_id)
    {
        $office = OfficeMain::findOne($office_id);

        if ($office->load(Yii::$app->request->post()) && $office->validate()) {
            if (OfficeRepository::saveOfficeFolders($office)) {
                MessageHandler::ShowMessage('با موفقیت ذخیره شد', 'success');
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                MessageHandler::ShowMessage("خطا در ذخیره سازی اطلاعات", "error");
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('UpdateLetterFolder', [
            'office' => $office,
            'folders' => OfficeFolderType::fetchAsDropDownArray(true),
        ]);
    }

    /**
     * this method is for signing the systematic letters
     * @param $id
     * @return string
     * @author Mehran
     * @throws $mysqli_sql_exception
     */
    public function actionConfirmationLetter($id)
    {
        if (OfficeRepository::confirmationLetter(OfficeMain::findOne($id))) {
            MessageHandler::ShowMessage('با موفقیت تایید شد', 'success');
            return $this->redirect('/secretariat_v2/secretariat/view-letter?id=' . $id);
        } else {
            MessageHandler::ShowMessage("خطا در ذخیره سازی اطلاعات", "error");
            return $this->redirect('/secretariat_v2/secretariat/view-letter?id=' . $id);
        }
    }

    public function actionDownloadLetter($id)
    {
        OfficeRepository::downloadLetter(OfficeMain::findOne($id));
    }

    /**
     * this method is will respond input letter with systematic out put respond
     * @param $id
     * @return string
     * @author Mehran
     * @throws $mysqli_sql_exception
     */
    public function actionRenderLastLetter($id)
    {
        $office_output = new OfficeOutput();

        $office_main = OfficeMain::findOne($id);
        $letter_info = OfficeRepository::getRenderInformation($office_main);

        if ($office_output->load(Yii::$app->request->post())) {
            $office_output->checkAvailability('content');
            $result = OfficeRepository::convertLetterToOutput($office_main, $office_output, $letter_info);
            if ($result) {
                MessageHandler::ShowMessage(' نامه به نامه خروجی تبدیل شد!', 'success');
                return $this->redirect('/secretariat_v2/secretariat/list-letters');
            }
            MessageHandler::ShowMessage('مشکلی در تبدیل نامه وجود دارد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('RenderLastLetter',[
            'office_output' => $office_output,
            'letter_info' => $letter_info
        ]);
    }

    /**
     * this method is will change the categpry of a letter
     * @param $office_id
     * @return string
     * @author Mehran
     * @throws $mysqli_sql_exception
     */
    public function actionChangeLetterCategory($office_id)
    {
        $office_main = OfficeMain::findOne($office_id);
        if (!CommonRepository::checkAvailability($office_main)) {
            MessageHandler::ShowMessage('نامه مورد نظر یافت نشد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($office_main->load(Yii::$app->request->post())) {
            if (OfficeRepository::changeCategory($office_main)) {
                MessageHandler::ShowMessage('دسته بندی با موفقیت ویرایش شد!', 'success');
                return $this->redirect('/secretariat_v2/secretariat/view-letter?id=' . $office_id);
            }
            MessageHandler::ShowMessage('امکان ویرایش دسته بندی وجود ندارد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('ChangeLetterCategory', [
            'office_main' => $office_main
        ]);
    }
}