<?php

namespace app\modules\secretariat_v2\models;

use app\models\traits\KartikHelper;
use app\modules\manage\models\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "office_setting".
 *
 * @property int $id
 * @property int $assign_admin_id
 * @property int $people_admin_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property User $assignAdmin
 * @property User $createdBy
 * @property OfficePeople $peopleAdmin
 * @property User $updatedBy
 */
class OfficeSetting extends \yii\db\ActiveRecord
{
    use KartikHelper;

    public function behaviors()
    {
        return [
            'class' => TimestampBehavior::className(),
            'class1' => BlameableBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'office_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assign_admin_id', 'people_admin_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['assign_admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['assign_admin_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['people_admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficePeople::className(), 'targetAttribute' => ['people_admin_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'assign_admin_id' => 'Assign Admin ID',
            'people_admin_id' => 'People Admin ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignAdmin()
    {
        return $this->hasOne(User::className(), ['id' => 'assign_admin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeopleAdmin()
    {
        return $this->hasOne(OfficePeople::className(), ['id' => 'people_admin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
