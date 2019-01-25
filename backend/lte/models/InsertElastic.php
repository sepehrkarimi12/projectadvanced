<?php
namespace app\modules\lte\models;


use app\modules\manage\models\User;
use app\modules\reseller\models\Reseller;
use yii\elasticsearch\ActiveRecord;

/**
 * This is the model class for table "lte_number_range".
 *
 * @property int $id
 * @property string $from
 * @property string $to
 * @property int $reseller_id
 * @property int $parent_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property LteNumberList[] $lteNumberLists
 * @property User $createdBy
 * @property LteNumberRange $parent
 * @property LteNumberRange[] $lteNumberRanges
 * @property Reseller $reseller
 * @property User $updatedBy
 */
class InsertElastic extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'reseller_id',
            'parent_id',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'from',
            'to'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lte_number_range';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reseller_id', 'parent_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['from', 'to'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => LteNumberRange::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['reseller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reseller::className(), 'targetAttribute' => ['reseller_id' => 'id']],
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
            'from' => 'From',
            'to' => 'To',
            'reseller_id' => 'Reseller ID',
            'parent_id' => 'Parent ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLteNumberLists()
    {
        return $this->hasMany(LteNumberList::className(), ['lte_number_range_id' => 'id']);
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
    public function getParent()
    {
        return $this->hasOne(LteNumberRange::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLteNumberRanges()
    {
        return $this->hasMany(LteNumberRange::className(), ['parent_id' => 'id']);
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}