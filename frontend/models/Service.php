<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property int $id
 * @property string $name
 * @property int $customer_id
 * @property int $type_id
 * @property int $network_id
 * @property string $address
 * @property string $ppoe_username
 * @property string $ppoe_password
 * @property int $status
 * @property int $creator_id
 * @property int $created_at
 * @property int $deletor_id
 * @property int $deleted_at
 *
 * @property Customer $customer
 * @property Network $network
 * @property Servicetype $type
 */
class Service extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'customer_id', 'type_id', 'network_id', 'address', 'ppoe_username', 'ppoe_password'], 'required'],
            [['customer_id', 'type_id', 'network_id', 'status', 'creator_id', 'created_at', 'deletor_id', 'deleted_at'], 'integer'],
            [['name', 'ppoe_username', 'ppoe_password'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 200],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['network_id'], 'exist', 'skipOnError' => true, 'targetClass' => Network::className(), 'targetAttribute' => ['network_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Servicetype::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'network_id' => Yii::t('app', 'Network ID'),
            'address' => Yii::t('app', 'Address'),
            'ppoe_username' => Yii::t('app', 'Ppoe Username'),
            'ppoe_password' => Yii::t('app', 'Ppoe Password'),
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
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetwork()
    {
        return $this->hasOne(Network::className(), ['id' => 'network_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Servicetype::className(), ['id' => 'type_id']);
    }
}
