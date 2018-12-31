<?php
namespace common\components;
use frontend\models\Customer;
use frontend\models\Network;
use yii\helpers\ArrayHelper;
use Yii;
 
abstract class Zmodel extends \yii\db\ActiveRecord
{
    // is deleted or not
	public static $active = 1;

    // need ip or not
    public static $ip = 1;

	public static function getAllCustomers()
    {
        $all=Customer::find()->where(['!=','is_deleted',self::$active])->all();
        $all=ArrayHelper::map(
            $all,
            'id',
            function($model) {
                return $model->fname.' '.$model['lname'];
            }
        );
        return $all;
    }

    public function getAllNetworks()
    {
        // $all=Network::find()->where(['!=','is_deleted',Zmodel::$active])->all();
        $all=Network::find()
        ->where(['!=','network.is_deleted',Zmodel::$active])
        ->joinWith('type')
        ->onCondition(['!=','networktype.is_deleted',Zmodel::$active])
        ->all();
        return ArrayHelper::map($all,'id','name');
    }

    public static function runPermissions()
    {
        
        $auth= Yii::$app->authManager;

        $superadmin = $auth->createRole('super admin');
        $auth->add($superadmin);

        // comment permissions
        $comment=$auth->createPermission('admin comment');
        $auth->add($comment);

        $comment=$auth->createPermission('add comment');
        $auth->add($comment);

        $comment=$auth->createPermission('update comment');
        $auth->add($comment);

        $comment=$auth->createPermission('delete comment');
        $auth->add($comment);

        // customer permissions
        $customer=$auth->createPermission('admin customer');
        $auth->add($customer);

        $customer=$auth->createPermission('add customer');
        $auth->add($customer);

        $customer=$auth->createPermission('update customer');
        $auth->add($customer);

        $customer=$auth->createPermission('delete customer');
        $auth->add($customer);

        // network permissions
        $network=$auth->createPermission('admin network');
        $auth->add($network);

        $network=$auth->createPermission('add network');
        $auth->add($network);

        $network=$auth->createPermission('update network');
        $auth->add($network);

        $network=$auth->createPermission('delete network');
        $auth->add($network);

        // networktype permissions
        $networktype=$auth->createPermission('admin networktype');
        $auth->add($networktype);

        $networktype=$auth->createPermission('add networktype');
        $auth->add($networktype);

        $networktype=$auth->createPermission('update networktype');
        $auth->add($networktype);

        $networktype=$auth->createPermission('delete networktype');
        $auth->add($networktype);

        // radio permissions
        $radio=$auth->createPermission('admin radio');
        $auth->add($radio);

        $radio=$auth->createPermission('add radio');
        $auth->add($radio);

        $radio=$auth->createPermission('update radio');
        $auth->add($radio);

        $radio=$auth->createPermission('delete radio');
        $auth->add($radio);

        // role permissions
        $role=$auth->createPermission('admin role');
        $auth->add($role);
        $auth->addChild($superadmin, $role);

        $role=$auth->createPermission('add role');
        $auth->add($role);
        $auth->addChild($superadmin, $role);

        $role=$auth->createPermission('update role');
        $auth->add($role);
        $auth->addChild($superadmin, $role);

        $role=$auth->createPermission('delete role');
        $auth->add($role);
        $auth->addChild($superadmin, $role);

        // service permissions
        $service=$auth->createPermission('admin service');
        $auth->add($service);

        $service=$auth->createPermission('add service');
        $auth->add($service);

        $service=$auth->createPermission('update service');
        $auth->add($service);

        $service=$auth->createPermission('delete service');
        $auth->add($service);

        // servicetype permissions
        $servicetype=$auth->createPermission('admin servicetype');
        $auth->add($servicetype);

        $servicetype=$auth->createPermission('add servicetype');
        $auth->add($servicetype);

        $servicetype=$auth->createPermission('update servicetype');
        $auth->add($servicetype);

        $servicetype=$auth->createPermission('delete servicetype');
        $auth->add($servicetype);

        // user permissions
        $user=$auth->createPermission('admin user');
        $auth->add($user);

        $user=$auth->createPermission('add user');
        $auth->add($user);

        $user=$auth->createPermission('update user');
        $auth->add($user);

        $user=$auth->createPermission('delete user');
        $auth->add($user);

        $auth->assign($superadmin, 1);
    }

    public static function deletePermissions()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }

}

?>