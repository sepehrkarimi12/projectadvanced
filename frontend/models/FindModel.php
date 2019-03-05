<?php

namespace frontend\models;

use yii\base\Model;
use yii\web\NotFoundHttpException;
use Yii;

class FindModel
{
	public static function findModel(Model $model,$id)
	{
		if (($model = $model::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}

?>
