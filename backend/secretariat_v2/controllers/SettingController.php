<?php

namespace app\modules\secretariat_v2\controllers;

use app\models\MessageHandler;
use app\modules\secretariat_v2\models\OfficeCharacter;
use app\modules\secretariat_v2\models\OfficePrefixFormat;
use app\modules\secretariat_v2\models\OfficeType;
use app\modules\secretariat_v2\models\searchModels\OfficeCharacterSearch;
use app\modules\secretariat_v2\models\searchModels\OfficeFormatSearch;
use app\modules\secretariat_v2\models\searchModels\OfficeLetterCharacterSearch;
use app\modules\secretariat_v2\repositories\CommonRepository;
use app\modules\secretariat_v2\repositories\SettingRepository;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class SettingController extends Controller
{
    public $layout = '@app/views/layouts/ManageMain';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['office-assign-setting'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('SecretariatV2SettingOfficeSettingPerm');
                        }
                    ],
                    [
                        'actions' => ['office-people-setting'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('SecretariatV2SettingOfficePeopleSettingPerm');
                        }
                    ],
                    [
                        'actions' => ['office-list-character-setting'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('SecretariatV2SettingOfficeListCharacterSettingPerm');
                        }
                    ],
                    [
                        'actions' => ['office-add-character-setting'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('SecretariatV2SettingOfficeAddCharacterSettingPerm');
                        }
                    ],
                    [
                        'actions' => ['office-update-character-setting'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2SettingOfficeUpdateCharacterSettingPerm');
//                        }
                    ],
                    [
                        'actions' => ['office-add-format-setting'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('SecretariatV2SettingOfficeAddFormatSettingPerm');
                        }
                    ],
                    [
                        'actions' => ['office-add-format-setting'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('SecretariatV2SettingOfficeListFormatSettingPerm');
                        }
                    ],
                    [
                        'actions' => ['office-list-format-setting'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('SecretariatV2SettingOfficeListFormatSettingPerm');
                        }
                    ],
                    [
                        'actions' => ['office-update-format-setting'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2SettingOfficeUpdateFormatSettingPerm');
//                        }
                    ],
                    [
                        'actions' => ['office-letter-character-setting'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2SettingOfficeLetterCharacterSettingPerm');
//                        }
                    ],
                    [
                        'actions' => ['office-set-letter-character'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2SettingOfficeSetLetterCharacterPerm');
//                        }
                    ],
                    [
                        'actions' => ['office-set-letter-format'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2SettingOfficeSetLetterFormatPerm');
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

    public function actionOfficeAssignSetting()
    {
        $office_setting = SettingRepository::officeSetting();

        if ($office_setting->load(Yii::$app->request->post())) {
            if (SettingRepository::officeAssignLetterSetting($office_setting)) {
                MessageHandler::ShowMessage('تغییرات با موفقیت اعمال شد!', 'success');
                return $this->redirect(Yii::$app->request->referrer);
            }
            MessageHandler::ShowMessage('مشکلی ذر اعمال تغییرات وجود دارد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('OfficeAssignSetting', [
            'office_setting' => $office_setting,
        ]);
    }

    public function actionOfficePeopleSetting()
    {
        $office_setting = SettingRepository::officeSetting();

        if ($office_setting->load(Yii::$app->request->post())) {
            if (SettingRepository::peopleLetterSetting($office_setting)) {
                MessageHandler::ShowMessage('تغییرات با موفقیت اعمال شد!', 'success');
                return $this->redirect(Yii::$app->request->referrer);
            }
            MessageHandler::ShowMessage('مشکلی ذر اعمال تغییرات وجود دارد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('OfficePeopleSetting', [
            'office_setting' => $office_setting,
        ]);
    }

    public function actionOfficeListCharacterSetting()
    {
        $searchModel = Yii::createObject(OfficeCharacterSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('OfficeListCharacterSetting', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOfficeAddCharacterSetting()
    {
        $office_prefix = new OfficeCharacter();

        if ($office_prefix->load(Yii::$app->request->post())) {
            if (SettingRepository::officeCharacterSetting($office_prefix)) {
                MessageHandler::ShowMessage('کاراکتر جدید با موفقیت افزوده شد!', 'success');
                return $this->redirect('/secretariat_v2/setting/office-list-character-setting');
            }
            MessageHandler::ShowMessage('مشکلی دردرج اطلاعات وجود دارد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('OfficeCharacterSetting', [
            'office_prefix' => $office_prefix
        ]);
    }

    public function actionOfficeUpdateCharacterSetting($id)
    {
        $office_prefix = OfficeCharacter::findOne($id);
        if (!CommonRepository::checkAvailability($office_prefix)) {
            MessageHandler::ShowMessage('کاراکتر مورد نظر یافت نشد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($office_prefix->load(Yii::$app->request->post())) {
            if (SettingRepository::officeCharacterSetting($office_prefix)) {
                MessageHandler::ShowMessage('کاراکتر  با موفقیت ویرایش شد!', 'success');
                return $this->redirect('/secretariat_v2/setting/office-list-character-setting');
            }
            MessageHandler::ShowMessage('مشکلی در ویرایش اطلاعات وجود دارد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('OfficeUpdateCharacterSetting', [
            'office_prefix' => $office_prefix
        ]);
    }

    public function actionOfficeAddFormatSetting()
    {
        $office_format = new OfficePrefixFormat();

        if ($office_format->load(Yii::$app->request->post())) {
            if (CommonRepository::saveModel($office_format)) {
                MessageHandler::ShowMessage('فرمت  با موفقیت افزوده شد!', 'success');
                return $this->redirect('/secretariat_v2/setting/office-list-format-setting');
            }
            MessageHandler::ShowMessage('مشکلی دردرج اطلاعات وجود دارد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('OfficeAddFormatSetting', [
            'office_format' => $office_format
        ]);
    }

    public function actionOfficeListFormatSetting()
    {
        $searchModel = Yii::createObject(OfficeFormatSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('OfficeListFormatSetting', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOfficeUpdateFormatSetting($id)
    {
        $office_format = OfficePrefixFormat::findOne($id);
        if (!CommonRepository::checkAvailability($office_format)) {
            MessageHandler::ShowMessage('کاراکتر مورد نظر یافت نشد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($office_format->load(Yii::$app->request->post())) {
            if (CommonRepository::saveModel($office_format)) {
                MessageHandler::ShowMessage('فرمت با ویرایش افزوده شد!', 'success');
                return $this->redirect('/secretariat_v2/setting/office-list-format-setting');
            }
            MessageHandler::ShowMessage('مشکلی در ویرایش اطلاعات وجود دارد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('OfficeUpdateFormatSetting', [
            'office_format' => $office_format
        ]);
    }

    public function actionOfficeLetterCharacterSetting()
    {
        $searchModel = Yii::createObject(OfficeLetterCharacterSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('OfficeLetterCharacterSetting', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOfficeSetLetterCharacter($id)
    {
        $office_type = OfficeType::findOne($id);
        if (!CommonRepository::checkAvailability($office_type)) {
            pd("error");
        }

        if ($office_type->load(Yii::$app->request->post())) {
            if (CommonRepository::saveModel($office_type)) {
                MessageHandler::ShowMessage('تغییرات با موفقیت انجام شد!', 'success');
                return $this->redirect(Yii::$app->request->referrer);
            }
            MessageHandler::ShowMessage('ممشکلی در تنظیم نامه وجود دارد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('OfficeSetLetterCharacter', [
            'office_type' => $office_type
        ]);
    }

    public function actionOfficeSetLetterFormat($id)
    {
        $office_type = OfficeType::findOne($id);
        if (!CommonRepository::checkAvailability($office_type)) {
            pd("error");
        }

        if ($office_type->load(Yii::$app->request->post())) {
            if (CommonRepository::saveModel($office_type)) {
                MessageHandler::ShowMessage('تغییرات با موفقیت انجام شد!', 'success');
                return $this->redirect(Yii::$app->request->referrer);
            }
            MessageHandler::ShowMessage('ممشکلی در تنظیم نامه وجود دارد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('OfficeSetLetterFormat', [
            'office_type' => $office_type
        ]);
    }
}