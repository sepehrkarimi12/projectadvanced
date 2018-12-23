<?php

namespace frontend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "networktype".
 *
 * @property int $id
 * @property string $title
 * @property int $is_deleted
 * @property int $creator_id
 * @property int $created_at
 * @property int $deletor_id
 * @property int $deleted_at
 *
 * @property Network[] $networks
 * @property User $creator
 * @property User $deletor
 */
class Networktype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'networktype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['is_deleted', 'creator_id', 'created_at', 'deletor_id', 'deleted_at', 'need_ip'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['title'], 'unique'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
            [['deletor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['deletor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'creator_id' => Yii::t('app', 'Creator Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'deletor_id' => Yii::t('app', 'Deletor ID'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'need_ip' => Yii::t('app', 'Need IP'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetworks()
    {
        return $this->hasMany(Network::className(), ['type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletor()
    {
        return $this->hasOne(User::className(), ['id' => 'deletor_id']);
    }
}
