<?php
namespace common\components;
use frontend\models\Customer;
use yii\helpers\ArrayHelper;
 
abstract class Zmodel extends \yii\db\ActiveRecord
{
    // is deleted or not
	public static $active = 1;

    // need ip or not
    public static $ip = 1;

	public static function getAllCustomers()
    {
        $all=Customer::find()->where(['!=','is_deleted',1])->all();
        $all=ArrayHelper::map(
            $all,
            'id',
            function($model) {
                return $model->fname.' '.$model['lname'];
            }
        );
        return $all;
    }
}

?>