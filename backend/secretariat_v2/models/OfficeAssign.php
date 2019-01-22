<?php

namespace app\modules\secretariat_v2\models;

use app\models\traits\Exists;
use app\modules\manage\models\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "office_assign".
 *
 * @property int $id
 * @property int $office_id
 * @property int $user_id
 * @property int $parent_id
 * @property string $paraph
 * @property int $is_seen
 * @property int $depth
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property OfficeMain $office
 * @property User $parent
 * @property User $updatedBy
 * @property User $user
 */
class OfficeAssign extends \yii\db\ActiveRecord
{
    use Exists;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
            [
                'class' => BlameableBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'office_assign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['office_id', 'user_id', 'parent_id', 'is_seen', 'depth', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['paraph'], 'string'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeMain::className(), 'targetAttribute' => ['office_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'office_id' => 'Office ID',
            'user_id' => 'User ID',
            'parent_id' => 'Parent ID',
            'paraph' => 'Paraph',
            'is_seen' => 'Is Seen',
            'depth' => 'Depth',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
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
    public function getOffice()
    {
        return $this->hasOne(OfficeMain::className(), ['id' => 'office_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(User::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }



    public function getChildNodes()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'user_id']);
    }

    public function getParentNode()
    {
        return $this->hasOne(self::className(), ['user_id' => 'parent_id'])
            ->andOnCondition(['office_id' => $this->office_id]);
    }
}
