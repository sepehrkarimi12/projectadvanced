<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "network".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $type_id
 * @property string $ip_address
 * @property int $status
 * @property int $creator_id
 * @property int $created_at
 * @property int $deletor_id
 * @property int $deleted_at
 *
 * @property Networktype $type
 * @property Radio[] $radios
 * @property Service[] $services
 */
class Network extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'network';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'address', 'type_id'], 'required'],
            [['type_id', 'status', 'creator_id', 'created_at', 'deletor_id', 'deleted_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 200],
            [['ip_address'], 'string', 'max' => 15],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Networktype::className(), 'targetAttribute' => ['type_id' => 'id']],
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
            'address' => Yii::t('app', 'Address'),
            'type_id' => Yii::t('app', 'Type ID'),
            'ip_address' => Yii::t('app', 'Ip Address'),
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
    public function getType()
    {
        return $this->hasOne(Networktype::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRadios()
    {
        return $this->hasMany(Radio::className(), ['network_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::className(), ['network_id' => 'id']);
    }
}
