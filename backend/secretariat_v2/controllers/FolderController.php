<?php

namespace app\modules\secretariat_v2\controllers;

use app\models\MessageHandler;
use app\modules\secretariat\models\Secretariat;
use app\modules\secretariat_v2\models\OfficeAccessLevel;
use app\modules\secretariat_v2\models\OfficeCategory;
use app\modules\secretariat_v2\models\OfficeFolderType;
use app\modules\secretariat_v2\models\OfficeMain;
use app\modules\secretariat_v2\models\OfficePriority;
use app\modules\secretariat_v2\models\OfficeType;
use app\modules\secretariat_v2\models\searchModels\OfficeFolderTypeSearch;
use app\modules\secretariat_v2\repositories\CommonRepository;
use app\modules\secretariat_v2\repositories\FolderRepository;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class FolderController extends Controller
{
    public $layout = '@app/views/layouts/ManageMain';

    

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['add-folder'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2AddFolderPerm');
//                        }
                    ],
                    [
                        'actions' => ['list-folder'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2ListFolderPerm');
//                        }
                    ],
                    [
                        'actions' => ['update-folder'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2UpdateFolderPerm');
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

    public function actionAddFolder()
    {
        $office_folder_type = new OfficeFolderType();

        if ($office_folder_type->load(Yii::$app->request->post())) {
            if (FolderRepository::addFolder($office_folder_type)) {
                MessageHandler::ShowMessage('پوشه با موفقیت افزوده شد.', 'success');
                return $this->redirect('/secretariat_v2/folder/list-folder');
            } else {
                MessageHandler::ShowMessage('مشکلی در درج پوشه وجود دارد.', 'error');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('AddFolder', [
            'folder_type' => $office_folder_type,
        ]);
    }

    public function actionListFolder($sub = 0)
    {
        $sub = intval($sub);

        $searchModel = Yii::createObject(OfficeFolderTypeSearch::className());
        $searchModel->sub = $sub;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('ListFolder', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionUpdateFolder($id)
    {
        $folder_type = OfficeFolderType::findOne($id);

        if (!CommonRepository::CheckAvailability($folder_type)) {
            MessageHandler::ShowMessage('پوشه مورد نظر یافت نشد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }
        if ($folder_type->load(Yii::$app->request->post())) {
            if (FolderRepository::updateLetter($folder_type)) {
                MessageHandler::ShowMessage('پوشه با موفقیت ویرایش شد.', 'success');
                return $this->redirect('/secretariat_v2/folder/list-folder');
            } else {
                MessageHandler::ShowMessage('مشکلی در ویرایش پوشه وجود دارد.', 'error');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('UpdateFolder', [
            'folder_type' => $folder_type,
        ]);
    }
}