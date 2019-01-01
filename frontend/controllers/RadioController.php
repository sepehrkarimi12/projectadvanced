<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Radio;
use frontend\models\searchs\RadioSearch;
use common\components\Zcontroller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * RadioController implements the CRUD actions for Radio model.
 */
class RadioController extends Zcontroller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules'=>[
                [
                    'actions' => ['index'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function($rule, $action) {
                        return Yii::$app->user->can('admin radio');
                    }
                ],
                [
                    'actions' => ['view'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function($rule, $action) {
                        return Yii::$app->user->can('admin radio');
                    }
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function($rule, $action) {
                        return Yii::$app->user->can('add radio');
                    }
                ],
                [
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function($rule, $action) {
                        return Yii::$app->user->can('update radio');
                    }
                ],
                [
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function($rule, $action) {
                        return Yii::$app->user->can('delete radio');
                    }
                ],
            ]                    
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Radio models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RadioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Radio model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => \frontend\models\FindModel::findModel(new Radio, $id),
        ]);
    }

    /**
     * Creates a new Radio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Radio();
        $networks = $model->getAllNetworks();

        if ($model->load(Yii::$app->request->post())) {
            $model = $this->save_customize($model);
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'networks' => $networks,
        ]);
    }

    /**
     * Updates an existing Radio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = \frontend\models\FindModel::findModel(new Radio, $id);
        $networks = $model->getAllNetworks();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
             'networks' => $networks,   
        ]);
    }

    /**
     * Deletes an existing Radio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = \frontend\models\FindModel::findModel(new Radio, $id);
        $model = $this->delete_customize($model);
        $model->save();

        return $this->redirect(['index']);
    }

}
