<?php

namespace frontend\models;

use common\models\User;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "servicetype".
 *
 * @property int $id
 * @property string $title
 * @property string $creator_username
 * @property int $is_deleted
 * @property int $creator_id
 * @property int $created_at
 * @property int $deletor_id
 * @property int $deleted_at
 *
 * @property Service[] $services
 * @property User $creator
 * @property User $deletor
 */
class Servicetype extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servicetype';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'creator_username',
                ],
                'value' => function ($event) {
                    return Yii::$app->user->identity->username;
                }
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['is_deleted', 'creator_id', 'created_at', 'deletor_id', 'deleted_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::className(), ['type_id' => 'id']);
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

    // chain methods for save
    public function setCreateTime($model)
    {
        $model->created_at = time();
        return $this;

    }

    public function setCreatorId($model)
    {
        $model->creator_id = Yii::$app->user->id;
        return $this;
    }

    // chain methods for delete

    public function setDeletedTime($model)
    {
        $model->deleted_at = time();
        return $this;
    }

    public function setDeletorId($model)
    {
        $model->deletor_id = Yii::$app->user->id;
        return $this;
    }

    public function setIsDeleted($model)
    {
        $model->is_deleted = 1;
        return $this;
    }

}
