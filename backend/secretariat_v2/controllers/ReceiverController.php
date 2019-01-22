<?php

namespace app\modules\secretariat_v2\controllers;

use app\modules\secretariat_v2\models\OfficePeople;
use app\modules\secretariat_v2\models\searchModels\OfficePeopleSearch;
use app\modules\secretariat_v2\repositories\CommonRepository;
use app\modules\secretariat_v2\repositories\ReceiverRepository;
use Exception;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\MessageHandler;
use app\modules\reseller\models\Reseller;

class ReceiverController extends Controller
{
    public $layout = '@app/views/layouts/ManageMain';

    

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['add-receiver'],
                        'allow' => true,
                        'roles' => ['@'],
//                       'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2ReceiverAddreceiverPerm');
//                        }
                    ],
                    [
                        'actions' => ['list-receivers'],
                        'allow' => true,
                        'roles' => ['@'],
//                       'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2ReceiverListreceiversPerm');
//                        }
                    ],
                    [
                        'actions' => ['update-receiver'],
                        'allow' => true,
                        'roles' => ['@'],
//                       'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2ReceiverUpdatereceiverPerm');
//                        }
                    ],
                    [
                        'actions' => ['declare-person'],
                        'allow' => true,
                        'roles' => ['@'],
//                       'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('SecretariatV2ReceiverDeclarePersonPerm');
//                        }
                    ],
                    [
                        'actions' => ['show-tree-inside-organization'],
                        'allow' => true,
                        'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('SecretariatV2ReceiverShowTreeInsideOrganizationPerm');
                        }
                    ],
                    [
                        'actions' => ['show-tree-outside-organization'],
                        'allow' => true,
                        'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('SecretariatV2ReceiverShowTreeOutsideOrganizationPerm');
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

    public function actionAddReceiver()
    {
        $receiver = new OfficePeople();

        if ($receiver->load(Yii::$app->request->post()) && $receiver->validate()) {
            if (ReceiverRepository::addReceiver($receiver)) {
                MessageHandler::ShowMessage('شخص با موفقیت افزوده شد.', 'success');
                return $this->redirect('/secretariat_v2/receiver/list-receivers');
            }
            MessageHandler::ShowMessage('مشکلی در افزودن شخص وجود دارد!', 'success');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('AddReceiver',[
            'receiver' => $receiver
        ]);
    }

    public function actionListReceivers($sub = 0)
    {
        $sub_users = intval($sub);
        $searchModel = Yii::createObject(OfficePeopleSearch::className());
        $searchModel->sub_users = $sub_users;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('ListReceivers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionUpdateReceiver($id)
    {
        $receiver = OfficePeople::findOne($id);

        if (!CommonRepository::checkAvailability($receiver)) {
            Throw new Exception("People not found!!!");
        }

        /**
         * Fetching returned user reseller_id.
         * Check access to user for reseller.
         */
        if (!Reseller::checkCurrentUserResellerAccess($receiver->reseller_id)) {
            Throw new Exception("Reseller access denied!");
        }

        if ($receiver->load(Yii::$app->request->post()) && $receiver->validate()) {
            if (ReceiverRepository::updateReceiver($receiver)) {
                MessageHandler::ShowMessage('شخص با موفقیت بروزرسانی شد.', 'success');
                return $this->redirect('/secretariat_v2/receiver/list-receivers');
            }
            MessageHandler::ShowMessage('مشکلی در بروزرسانی شخص وجود دارد!', 'success');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('UpdateReceiver',[
            'receiver' => $receiver
        ]);
    }

    public function actionDeclarePerson($id)
    {
        $office_people = OfficePeople::findOne($id);

        if (!CommonRepository::checkAvailability($office_people)) {
            Throw new Exception("People not found!!!");
        }

        if ($office_people->load(Yii::$app->request->post())) {
            if (ReceiverRepository::updateReceiver($office_people)) {
                MessageHandler::ShowMessage('شخص با موفقیت تعیین شد.', 'success');
                return $this->redirect('/secretariat_v2/receiver/list-receivers');
            }
            MessageHandler::ShowMessage('مشکلی در تعیین شخص وجود دارد!', 'success');
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('DeclarePerson', [
           'office_people' => $office_people
        ]);
    }

    public function actionShowTreeInsideOrganization()
    {
        $in_organization = ReceiverRepository::showTree(1);

        return $this->render('ShowTree', [
            'organization' => $in_organization
        ]);
    }

    public function actionShowTreeOutsideOrganization()
    {
        $out_organization = ReceiverRepository::showTree(2);

        return $this->render('ShowTree', [
            'organization' => $out_organization
        ]);
    }
}