<?php

namespace app\modules\secretariat_v2\models;

use app\modules\manage\models\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "office_relation".
 *
 * @property int $id
 * @property int $office_id
 * @property int $office_relation_id
 * @property int $office_reltype_id
 * @property int $family_id
 * @property int $depth
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property OfficeMain $family
 * @property OfficeMain $office
 * @property OfficeMain $officeRelation
 * @property OfficeRelationType $officeReltype
 * @property User $updatedBy
 */
class OfficeRelation extends \yii\db\ActiveRecord
{

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
        return 'office_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['office_id', 'office_relation_id', 'office_reltype_id', 'family_id', 'depth', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['family_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeMain::className(), 'targetAttribute' => ['family_id' => 'id']],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeMain::className(), 'targetAttribute' => ['office_id' => 'id']],
            [['office_relation_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeMain::className(), 'targetAttribute' => ['office_relation_id' => 'id']],
            [['office_reltype_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeRelationType::className(), 'targetAttribute' => ['office_reltype_id' => 'id']],
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
            'office_id' => 'Office ID',
            'office_relation_id' => 'Office Relation ID',
            'office_reltype_id' => 'Office Reltype ID',
            'family_id' => 'Family ID',
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
    public function getFamily()
    {
        return $this->hasOne(OfficeMain::className(), ['id' => 'family_id']);
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
    public function getOfficeRelation()
    {
        return $this->hasOne(OfficeMain::className(), ['id' => 'office_relation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeReltype()
    {
        return $this->hasOne(OfficeRelationType::className(), ['id' => 'office_reltype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
