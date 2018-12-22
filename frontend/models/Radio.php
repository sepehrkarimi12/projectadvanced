<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "radio".
 *
 * @property int $id
 * @property string $name
 * @property string $model
 * @property string $serial
 * @property int $network_id
 * @property int $status
 * @property int $creator_id
 * @property int $created_at
 * @property int $deletor_id
 * @property int $deleted_at
 *
 * @property Network $network
 */
class Radio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'radio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'model', 'serial', 'network_id'], 'required'],
            [['network_id', 'status', 'creator_id', 'created_at', 'deletor_id', 'deleted_at'], 'integer'],
            [['name', 'model', 'serial'], 'string', 'max' => 100],
            [['network_id'], 'exist', 'skipOnError' => true, 'targetClass' => Network::className(), 'targetAttribute' => ['network_id' => 'id']],
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
            'model' => Yii::t('app', 'Model'),
            'serial' => Yii::t('app', 'Serial'),
            'network_id' => Yii::t('app', 'Network ID'),
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
    public function getNetwork()
    {
        return $this->hasOne(Network::className(), ['id' => 'network_id']);
    }
}
