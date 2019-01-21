<?php

namespace app\modules\secretariat_v2\models;

use app\models\interfaces\KartikInterface;
use app\models\traits\KartikHelper;
use app\modules\manage\models\User;
use app\modules\reseller\models\Reseller;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "office_character".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $reseller_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property Reseller $reseller
 * @property User $updatedBy
 * @property OfficeType[] $officeTypes
 */
class OfficeCharacter extends \yii\db\ActiveRecord implements KartikInterface
{

    /**
     * @return array
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
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'reseller_id',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'reseller_id',
                ],
                'value' => function ($event) {
                    return Yii::$app->user->identity->reseller_id;
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'office_character';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reseller_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['description'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 1],

            [['name'], 'required', 'message' => 'وارد کردن کاراکتر الزامی می باشد.'],
            [['description'], 'required', 'message' => 'وارد کردن توضیحات اجباری می باشد.'],

            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'name' => 'Name',
            'description' => 'Description',
            'reseller_id' => 'Reseller ID',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeTypes()
    {
        return $this->hasMany(OfficeType::className(), ['character_id' => 'id']);
    }

    public static function fetchAsDropDownArray($reseller = false) : array
    {
        $types = self::find()->where(['reseller_id' => Yii::$app->user->identity->reseller_id])->all();

        $result = [];

        if ($types != null) {
            foreach ($types as $key => $value) {
                $data[$value->id]['id'] = $value->id;
                $data[$value->id]['name'] = $value->name . ' - ' . $value->description;
            }
            $result = ArrayHelper::map($data, 'id', 'name');
        }

        return $result;
    }
}
