<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Comment[] $comments
 * @property Comment[] $comments0
 * @property Customer[] $customers
 * @property Customer[] $customers0
 * @property Network[] $networks
 * @property Network[] $networks0
 * @property Networktype[] $networktypes
 * @property Networktype[] $networktypes0
 * @property Radio[] $radios
 * @property Radio[] $radios0
 * @property Service[] $services
 * @property Service[] $services0
 * @property Servicetype[] $servicetypes
 * @property Servicetype[] $servicetypes0
 */
class User extends \yii\db\ActiveRecord
{
    public $password;
    public $role;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at', 'password', 'role'], 'required', 'on'=>'add_user'],

            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'],
             'required', 'on'=>'edit_user'],

            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['password'],'string','min'=>6 ],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments0()
    {
        return $this->hasMany(Comment::className(), ['deletor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers0()
    {
        return $this->hasMany(Customer::className(), ['deletor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetworks()
    {
        return $this->hasMany(Network::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetworks0()
    {
        return $this->hasMany(Network::className(), ['deletor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetworktypes()
    {
        return $this->hasMany(Networktype::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetworktypes0()
    {
        return $this->hasMany(Networktype::className(), ['deletor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRadios()
    {
        return $this->hasMany(Radio::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRadios0()
    {
        return $this->hasMany(Radio::className(), ['deletor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices0()
    {
        return $this->hasMany(Service::className(), ['deletor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicetypes()
    {
        return $this->hasMany(Servicetype::className(), ['creator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicetypes0()
    {
        return $this->hasMany(Servicetype::className(), ['deletor_id' => 'id']);
    }

    public function save($runValidation=true,$attributeNames=true)
    {
        $user = new \common\models\User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->status = $this->status ? 10 : 0;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        if($user->save())
        {

            $assign = new Authassignment;

            $assign->user_id=$user->id;
            $assign->item_name=$this->role;

            $this->id=$user->id;
            return true;
        }
        return false;
    }

    public function update($runValidation = true, $attributeNames = NULL)
    {
        if(!empty($this->password))
        {
            $this->password_hash=Yii::$app->security->generatePasswordHash($this->password);
        }
        $this->status = $this->status ? 10 : 0;
        return parent::update();
    }

    public function getAllRoles()
    {
        $all=Role::findAll(['type'=>1]);
        return ArrayHelper::map($all,'name','name');
    }

}
