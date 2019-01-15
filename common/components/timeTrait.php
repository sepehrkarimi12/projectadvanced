<?php
namespace common\components;

use Yii;

// used this trait in comment model
trait timeTrait
{
	public function save_customize_trait($model)
	{
		$model->created_at = time();
        $model->creator_id = Yii::$app->user->id;
        return $model;
	}

	public function delete_customize_trait($model)
	{
		$model->deleted_at = time();
        $model->deletor_id = Yii::$app->user->id;
        $model->is_deleted = 1;
        return $model;
	}
}

?>