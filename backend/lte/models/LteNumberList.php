<?php

namespace app\modules\lte\models;

use app\modules\manage\models\Lte;
use app\modules\manage\models\User;
use app\modules\reseller\models\Reseller;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "lte_number_purchase".
 *
 * @property int $id
 * @property string $number
 * @property int $lte_range_id
 * @property int $lte_service_id
 * @property int $reseller_id
 * @property int $flag_id
 * @property int $status_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property LteNumberFlag $flag
 * @property LteNumberRange $lteRange
 * @property Lte $lteService
 * @property Reseller $reseller
 * @property LteNumberStatus $status
 * @property User $updatedBy
 */
class LteNumberList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lte_number_list';
    }

    /**
     * {@inheritdoc}
     */
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
    public function rules()
    {
        return [
            [['lte_range_id', 'lte_service_id', 'reseller_id', 'flag_id', 'status_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['number'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['flag_id'], 'exist', 'skipOnError' => true, 'targetClass' => LteNumberFlag::className(), 'targetAttribute' => ['flag_id' => 'id']],
            [['lte_range_id'], 'exist', 'skipOnError' => true, 'targetClass' => LteNumberRange::className(), 'targetAttribute' => ['lte_range_id' => 'id']],
            [['lte_service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lte::className(), 'targetAttribute' => ['lte_service_id' => 'id']],
            [['reseller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reseller::className(), 'targetAttribute' => ['reseller_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => LteNumberStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
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
            'number' => 'Number',
            'lte_range_id' => 'Lte Range ID',
            'lte_service_id' => 'Lte Service ID',
            'reseller_id' => 'Reseller ID',
            'flag_id' => 'Flag ID',
            'status_id' => 'Status ID',
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
    public function getFlag()
    {
        return $this->hasOne(LteNumberFlag::className(), ['id' => 'flag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLteRange()
    {
        return $this->hasOne(LteNumberRange::className(), ['id' => 'lte_range_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLteService()
    {
        return $this->hasOne(Lte::className(), ['id' => 'lte_service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReseller()
    {
        return $this->hasOne(Reseller::className(), ['id' => 'reseller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(LteNumberStatus::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
