<?php

namespace app\modules\secretariat_v2\controllers;

use app\models\MessageHandler;
use app\modules\secretariat_v2\models\OfficeAssign;
use app\modules\secretariat_v2\models\OfficeMain;
use app\modules\secretariat_v2\repositories\AssignRepository;
use app\modules\secretariat_v2\repositories\CommonRepository;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class AssignController extends Controller
{
    public $layout = '@app/views/layouts/ManageMain';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['assign-letter'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2AssignLetterPerm');
//                        }
                    ],
                    [
                        'actions' => ['delete-assign'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatAssignLetterPerm');
//                        }
                    ],
                    [
                        'actions' => ['update-assign'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2AssignLetterPerm');
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

    public function actionAssignLetter($office_id)
    {
        $office_main = OfficeMain::findOne($office_id);
        if (!CommonRepository::checkAvailability($office_main)) {
            MessageHandler::ShowMessage('نامه یافت نشد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }
        /**
         * Find whom the secretariat is assigned
         */
        $assigned_employees = OfficeAssign::find()
            ->where(['office_id' => $office_id])
            ->andWhere(['parent_id' => Yii::$app->user->identity->id])
            ->all();

        $office_assign = new OfficeAssign();

        if ($office_assign->load(Yii::$app->request->post()) && $office_assign->validate()) {
            $office_assign->office_id = $office_main->id;
            $assign_result = AssignRepository::assignLetter($office_assign);

            MessageHandler::ShowMessage($assign_result['message'], $assign_result['status']);
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('AssignLetter', [
            'office_main' => $office_main,
            'office_assign' => $office_assign,
            'assigned_employees' => $assigned_employees,
        ]);
    }

    public function actionDeleteAssign($assign_id)
    {
        $office_assign = OfficeAssign::findOne($assign_id);
        if (!CommonRepository::checkAvailability($office_assign)) {
            MessageHandler::pd('ارجاع مورد نظز یافت نشد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if (AssignRepository::deleteAssign($office_assign)) {
            MessageHandler::ShowMessage('با موفقیت حذف شد', 'success');
            return $this->redirect(Yii::$app->request->referrer);
        }
        MessageHandler::ShowMessage(' مشکلی در حذف سرویس وجود دارد! ', 'error');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUpdateAssign($assign_id)
    {
        $office_assign = OfficeAssign::findOne($assign_id);
        if (!CommonRepository::checkAvailability($office_assign)) {
            MessageHandler::pd('ارجاع مورد نظز یافت نشد!', 'error');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($office_assign->load(Yii::$app->request->post()) && $office_assign->validate()) {
            $assign_result = AssignRepository::UpdateAssignLetter($office_assign);

            MessageHandler::ShowMessage($assign_result['message'], $assign_result['status']);
            return $this->redirect('/secretariat_v2/assign/assign-letter?office_id=' . $office_assign->office_id);
        }

        return $this->render('UpdateAssign', [
            'office_main' => $office_assign->office,
            'office_assign' => $office_assign,
        ]);
    }
}