<?php
namespace common\components;
use yii\web\Controller;
use frontend\models\Customer;
use Yii;

abstract class Zcontroller extends Controller
{

	public function save_customize($model)
	{
		$model->created_at=time();
        $model->creator_id=Yii::$app->user->id;
        return $model;
	}


	public function delete_customize($model)
	{
		$model->deleted_at=time();
        $model->deletor_id=Yii::$app->user->id;
        $model->is_deleted=1;
        return $model;
	}



}


?>