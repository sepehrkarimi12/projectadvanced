<?php

namespace app\modules\lte\controllers;

use app\models\MessageHandler;
use app\modules\lte\jobs\AddLteNumberJob;
use app\modules\lte\models\LteNumberRange;
use app\modules\lte\models\searchModel\LteNumberAssignRanjeSeach;
use app\modules\lte\models\searchModel\LteNumberRanjeSeach;
use app\modules\lte\repositories\BlockRepository;
use app\modules\secretariat_v2\repositories\CommonRepository;
use Exception;
use yii\filters\AccessControl;
use Yii;
use yii\web\ForbiddenHttpException;

class BlockController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/ManageMain';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('LteBlockCreatePerm');
//                        }
                    ],
                    [
                        'actions' => ['assign'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('LteBlockCreatePerm');
//                        }
                    ],
                    [
                        'actions' => ['list'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('LteBlockListPerm');
//                        }
                    ],
                    [
                        'actions' => ['assign-list'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('LteBlockAssignListPerm');
//                        }
                    ],
                    [
                        'actions' => ['update-assign'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('LteBlockUpdateAssignPerm');
//                        }
                    ],
                    [
                        'actions' => ['view-assign-block'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('LteBlockViewAssignBlockPerm');
//                        }
                    ],
                    [
                        'actions' => ['test'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
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

    public function actionTest()
    {
        $queue = Yii::$app->queue;

        pd($queue->run());
        $id = $queue->push(new AddLteNumberJob([
            'mobile_number' => '093905519801',
            'reseller_id' => Yii::$app->user->identity->reseller_id,
            'lte_range_id' => 1,
            'flag_id' => 1,
            'status_id' => 2
        ]));
        pd($id);
    }

    /**
     * Create a range for numbers without assigning to any servco
     *
     * @return string
     *
     * @author Noei
     */
    public function actionCreate()
    {
        $number_range = new LteNumberRange();
        $number_range->initialize = true;

        if ($number_range->load(Yii::$app->request->post())) {
            if ($number_range->save()) {
                Yii::$app->print->ShowMessage('اره ها با موفقیت در لیست پردازش قرار گرفت!', 'success', [$this, 'list']);
            } else {
                Yii::$app->print->ShowMessage('خطا در ذخیره سازی', 'error', [$this, 'create']);
            }
        }

        return $this->render('Create', [
            'number_range' => $number_range
        ]);
    }


    /**
     * Assign created range to servcos including the owner
     *
     * @return string
     *
     * @author Noei, Mehran
     *
     * @throws Exception
     */
    public function actionAssign($id)
    {
        $number_range = new LteNumberRange();
        if ($number_range->load(Yii::$app->request->post()) && $number_range->validate()) {
            if ($number_range->save()) {
                MessageHandler::ShowMessage('تخصیص شماره با موفقیت افزوده شد!','success');
                return $this->redirect('/lte/block/assign-list?id=' . $id);
            } else {
                MessageHandler::ShowMessage('امکان تخصیص شماره در این بازه وجود ندارد!','error');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('Assign', [
            'number_range' => $number_range
        ]);
    }

    /**
     * This action has been written for show list of Block Numbers
     * @return string
     * @throws Exception
     * @author Mehran, Noei
     */
    public function actionList()
    {
        try {
            $searchModel = new LteNumberRanjeSeach();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } catch(Exception $exception) {
            Throw new Exception("The LteNumberRanje not found!!!!");
        }

        return $this->render('List',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * This action has been work for show list of numbers for special range assigned to reseller
     * @param $id
     * @return string
     * @throws Exception
     * @author Mehran
     */
    public function actionAssignList($id)
    {
        try {
            $searchModel = new LteNumberAssignRanjeSeach();
            $searchModel->parent_reseller = $id;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } catch(Exception $exception) {
            Throw new Exception("The LteNumberRanje not found!!!!");
        }

        return $this->render('AssignList',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'range_id' => $id
        ]);
    }

    /**
     * This action has been used for update phone range assignment
     * @param $assign_id
     * @return string
     * @throws ForbiddenHttpException
     * @author Mehran
     * @version 1
     */
    public function actionUpdateAssign($assign_id)
    {
        $number_range = LteNumberRange::findOne($assign_id);
        if (!CommonRepository::checkAvailability($assign_id)) {
             Throw new ForbiddenHttpException('The LteNumberRange table not found!');
        }

        return $this->render('UpdateAssign', [
            'number_range' => $number_range
        ]);
    }

    /**
     * This action show all numbers belong to special resller for its range number
     * @param $assign_id
     * @return string
     * @author Mehran
     * @version 1
     */
    public function actionViewAssignBlock($assign_id)
    {
        $lte_numbers = BlockRepository::fetchLteRangeNumber($assign_id);
        $lte_numbers = BlockRepository::fetchLteRangeNumberStatus($lte_numbers);

        return $this->render('ViewAssignBlock',[
            'lte_numbers' => $lte_numbers
        ]);
    }
}