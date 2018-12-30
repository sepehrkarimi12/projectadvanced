<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Comment;
use frontend\models\searchs\CommentSearch;
// use yii\web\Controller;
use common\components\Zmodel;
use common\components\Zcontroller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends ZController
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
                        return Yii::$app->user->can('admin comment');
                    }
                ],
                [
                    'actions' => ['view'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback'=>function($rule,$action){
                        return Yii::$app->user->can('admin comment');
                    }
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback'=>function($rule,$action){
                        return Yii::$app->user->can('add comment');
                    }
                ],
                [
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback'=>function($rule,$action){
                        return Yii::$app->user->can('update comment');
                    }
                ],
                [
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback'=>function($rule,$action){
                        return Yii::$app->user->can('delete comment');
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
     * Lists all Comment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Comment model.
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
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Comment();
        $customers=[];
        $customer_name='';

        if( isset($_GET['id']) && isset($_GET['name']) )
        {
            $model->customer_id = $_GET['id'];
            $customer_name = $_GET['name'];
        }
        else
        {
            $customers=Zmodel::getAllCustomers();
        }
        // print_r($customers);
        // die();

        if ($model->load(Yii::$app->request->post()) ) {
            // echo !($model->imageFile);
            // die();
            if( empty($model->imageFile) ){            
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                $model->file=$model->upload();
            }

            $model=$this->save_customize($model);
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'customers' => $customers,
            'customer_name' => $customer_name,
        ]);
    }

    /**
     * Updates an existing Comment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {

            if( empty($model->imageFile) ){            
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                $model->file=$model->upload();
            }

            $oldfile=$this->findModel($id)->file;
            if($model->file==null){
                $model->file=$oldfile;
            }
            else
            {  
                if($oldfile!=null)
                    unlink($oldfile);
            }
            // echo();
            // die();

            $model=$this->save_customize($model);
            $model->update(false);

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();

        // return $this->redirect(['index']);
        $model=$this->findModel($id);
        $model=$this->delete_customize($model);
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
