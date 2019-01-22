<?php

namespace app\modules\secretariat_v2\models;

use app\models\interfaces\KartikInterface;
use app\modules\manage\models\User;
use app\modules\reseller\models\Reseller;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "office_people".
 *
 * @property int $id
 * @property string $name
 * @property string $company_name
 * @property string $occuaption_name
 * @property string $unit_name
 * @property string $family
 * @property int $reseller_id
 * @property string $tel
 * @property int $office_people_type_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property OfficePeopleType $officePeopleType
 * @property Reseller $reseller
 * @property User $updatedBy
 * @property OfficeTranscript[] $officeTranscripts
 */
class OfficePeople extends ActiveRecord implements KartikInterface
{

    private $model;

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
        return 'office_people';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reseller_id', 'office_people_type_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'company_name', 'occuaption_name', 'unit_name', 'family', 'tel'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['office_people_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficePeopleType::className(), 'targetAttribute' => ['office_people_type_id' => 'id']],
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
            'company_name' => 'Company Name',
            'occuaption_name' => 'Occuaption Name',
            'unit_name' => 'Unit Name',
            'family' => 'Family',
            'reseller_id' => 'Reseller ID',
            'tel' => 'Tel',
            'office_people_type_id' => 'Office People Type ID',
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
    public function getOfficePeopleType()
    {
        return $this->hasOne(OfficePeopleType::className(), ['id' => 'office_people_type_id']);
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
    public function getOfficeTranscripts()
    {
        return $this->hasMany(OfficeTranscript::className(), ['office_people_id' => 'id']);
    }

    /**
     * @return array
     * This method fetches all people based on their reseller and type
     * @param $type_id (inside company or outside company)
     * this method returns array suitable for drop down lists
     */
    public static function fetchAsDropDownArray($type_id = null): array
    {
        $people = self::find()
            ->joinWith('officePeopleType')
            ->select(['office_people.id', 'office_people.name', 'family', 'occuaption_name', 'unit_name', 'company_name', 'tel', 'office_people_type_id'])
            ->where(['reseller_id' => Yii::$app->user->identity->reseller_id])
            ->andFilterWhere(['office_people_type.id' => $type_id])
            ->asArray()->all();

        foreach ($people as $key => $value) {
            $data[$value['id']]['id'] = $value['id'];
            $data[$value['id']]['name'] = $value['name'] . ' ' . $value['family'] . ' ' . $value['occuaption_name'] . ' ' . $value['unit_name'] . ' ' . $value['company_name'] . ' - ' . $value['tel'];
        }
        $result = ArrayHelper::map($data, 'id', 'name');

        /*array_walk($people, function(&$value, $key){
            $key = 555555;
            pd($key);
            $value = $value['name']."-".$value['family']."-".$value['occuaption_name']."-".$value['unit_name']."-".$value['company_name'];
        });

        pd($people);*/
        return $result;
    }


}
