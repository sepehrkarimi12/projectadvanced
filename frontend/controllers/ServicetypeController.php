<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Servicetype;
use frontend\models\Networktype;
use frontend\models\searchs\ServicetypeSearch;
// use yii\web\Controller;
// use common\components\Zcontroller;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ServicetypeController implements the CRUD actions for Servicetype model.
 */
class ServicetypeController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                    'matchCallback' => function($rule, $action) {
                        return Yii::$app->user->can('admin servicetype');
                    }
                ],
                [
                    'actions' => ['view'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function($rule, $action) {
                        return Yii::$app->user->can('admin servicetype');
                    }
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function($rule, $action) {
                        return Yii::$app->user->can('add servicetype');
                    }
                ],
                [
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function($rule, $action) {
                        return Yii::$app->user->can('update servicetype');
                    }
                ],
                [
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function($rule, $action) {
                        return Yii::$app->user->can('delete servicetype');
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
     * Lists all Servicetype models.
     * @return mixed
     */
    public function actionIndex()
    {
//        echo "<pre>";
//        $all = Servicetype::find()->asArray()->limit(2)->one();
//        echo $all['title']. ' - ';
//        // echo $all[0]->title;
//        echo "<hr>";
//        print_r($all);
//        echo "<hr>";
//        foreach ($all as $key => $value) {
//            // $value['title'];
//            print_r( $value );
//        }
//        die;
        $searchModel = new ServicetypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // echo("<pre>");
        // Networktype::findOne(5)->delete();
        // die;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Servicetype model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => \frontend\models\FindModel::findModel(new Servicetype, $id),
        ]);
    }

    /**
     * Creates a new Servicetype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Servicetype();

        if ($model->load(Yii::$app->request->post())) {
            $model = $model->setCreateTime($model)->setCreatorId($model);
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Servicetype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = \frontend\models\FindModel::findModel(new Servicetype, $id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Servicetype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // \frontend\models\FindModel::findModel(new Servicetype, $id)->delete();
        $model = \frontend\models\FindModel::findModel(new Servicetype, $id);
        $model = $model->setDeletedTime($model)->setDeletorId($model)->setIsDeleted($model)
        ->save();

        return $this->redirect(['index']);
    }

}
