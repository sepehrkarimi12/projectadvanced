<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property string $fname
 * @property string $lname
 * @property string $address
 * @property string $email
 * @property string $phone
 * @property string $mobile
 * @property int $status
 * @property int $creator_id
 * @property int $created_at
 * @property int $deletor_id
 * @property int $deleted_at
 *
 * @property Comment[] $comments
 * @property Service[] $services
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fname', 'lname', 'address', 'phone', 'mobile'], 'required'],
            [['status', 'creator_id', 'created_at', 'deletor_id', 'deleted_at'], 'integer'],
            [['fname', 'lname'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 60],
            [['phone', 'mobile'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fname' => Yii::t('app', 'Fname'),
            'lname' => Yii::t('app', 'Lname'),
            'address' => Yii::t('app', 'Address'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'mobile' => Yii::t('app', 'Mobile'),
            'status' => Yii::t('app', 'Status'),
            'creator_id' => Yii::t('app', 'Creator ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'deletor_id' => Yii::t('app', 'Deletor ID'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::className(), ['customer_id' => 'id']);
    }
}
