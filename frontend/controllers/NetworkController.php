<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Network;
use frontend\models\searchs\NetworkSearch;
use common\components\Zcontroller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * NetworkController implements the CRUD actions for Network model.
 */
class NetworkController extends Zcontroller
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
                    'matchCallback'=>function($rule,$action){
                        return Yii::$app->user->can('admin network');
                    }
                ],
                [
                    'actions' => ['view'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback'=>function($rule,$action){
                        return Yii::$app->user->can('admin network');
                    }
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback'=>function($rule,$action){
                        return Yii::$app->user->can('add network');
                    }
                ],
                [
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback'=>function($rule,$action){
                        return Yii::$app->user->can('update network');
                    }
                ],
                [
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback'=>function($rule,$action){
                        return Yii::$app->user->can('delete network');
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
     * Lists all Network models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NetworkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Network model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Network model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Network();
        // $model->scenario = Network::SCENARIO_WITH_IP;
        $networktypes = $model->getAllNetworktypes();


        if ($model->load(Yii::$app->request->post())) {

            // In model we have to check NetworkType need IP or not so we use validate() method 
            if($model->validate()){
                $model=$this->save_customize($model);
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }

        }

        return $this->render('create', [
            'model' => $model,
            'networktypes' => $networktypes,
        ]);
    }

    /**
     * Updates an existing Network model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $networktypes = $model->getAllNetworktypes();


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
             'networktypes' => $networktypes,
        ]);
    }

    /**
     * Deletes an existing Network model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $model=$this->delete_customize($model);
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Network model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Network the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Network::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
