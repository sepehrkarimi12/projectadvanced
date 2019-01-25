<?php

namespace app\modules\lte\models;

use app\modules\lte\jobs\AddLteNumberJob;
use app\modules\manage\models\User;
use app\modules\reseller\models\Reseller;
use app\modules\secretariat_v2\repositories\CommonRepository;
use Exception;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "lte_number_range".
 *
 * @property int $id
 * @property string $from
 * @property string $to
 * @property int $reseller_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $initialize
 *
 * @property LteNumberList[] $lteNumberlists
 * @property User $createdBy
 * @property LteNumberRange[] $lteNumberRanges
 * @property Reseller $reseller
 * @property User $updatedBy
 */
class LteNumberRange extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE_RANGE = 'create_range_number';

    /**
     * set true if you are defining new range not assigning it
     *
     * @var bool
     *
     * @author Noei
     */
    public $initialize = false;

    /**
     * event for initializing new phone range
     *
     * @author Noei
     */
    const EVENT_INITIALIZE_RANGE = 'initialize-new-range';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lte_number_range';
    }

    /**
     * Check for availability of a block number
     * @author Neoi, Mehran
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMutualRanges()
    {
        return self::find()
            /**
             * check if start number is in any other range
             */
            ->where(['and', ['<=', 'from', $this->from], ['>=', 'to', $this->from]])
            /**
             * check if end number is in any other range
             */
            ->orWhere(['and', ['<=', 'from', $this->to], ['>=', 'to', $this->to]])
            ->all();
    }

    /**
     * Check the to field must be greater than from field
     * @author,ehran
     * @return array|\yii\db\ActiveRecord[]
     */
    public function validateRange()
    {
        if ($this->to < $this->from) {
            return "Error";
        }
    }

    /**
     * Check the assign range number when we want assign number to reseller
     * @author Mehran
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPurchaseRange()
    {
        $purchase_range = LteNumberList::find()
            ->where(['between', 'number', $this->from, $this->to])
            ->andWhere(['not', ['reseller_id' => null]])
            ->all();

        return $purchase_range;
    }

    /**
     * This method has been override for check ranje of number before save
     * In here, ranje of number has been checked, for before not assign to another reseller
     * @param bool $insert
     * @return bool
     * @author Shayan
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->initialize) {
            $this->trigger(self::EVENT_INITIALIZE_RANGE);

            if ($this->getMutualRanges() != null) {
                return false;
            }
        } else {
            if ($this->getPurchaseRange() != null) {
                return false;
            }
        }
        if ($this->validateRange()) {
            return false;
        }
        return true;
    }


    /**
     * This method push all numbers to queue
     * @param bool $insert
     * @param array $changedAttributes
     * @return bool|void
     * @author Mehran
     */
    public function afterSave($insert, $changedAttributes)
    {
        $queue = Yii::$app->queue;
        /**
         * This section for set reseller before declare range of number
         */
        if (parent::afterSave($insert, $changedAttributes)) {
            return false;
        }

        if ($this->initialize) {
            $ranje_number = $this->to - $this->from;
            $number = $this->from;
            for ($i = 0; $i <= $ranje_number; $i++) {
                if ($number <= $this->to) {
                    $queue->push(new AddLteNumberJob([
                        'mobile_number' => $number,
                        'reseller_id' => null,
                        'lte_range_id' => $this->id,
                        'flag_id' => 1,
                        'status_id' => 2
                    ]));
                }
                $number++;
                $number = '0' . $number;
            }
        }
        return true;
    }

    /**
     * set Reseller id before saving model
     */
    public function initializeResellerId()
    {
        if ($this->initialize) {
            $this->reseller_id = Yii::$app->user->identity->reseller_id;
        }
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

            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'reseller_id',

                ],
                'value' => function ($event) {
                    return Yii::$app->user->identity->reseller_id;
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->on(self::EVENT_INITIALIZE_RANGE, [$this, 'initializeResellerId']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reseller_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['initialize'], 'boolean'],
            [['from', 'to'], 'string', 'max' => 11],
            [['from'], 'required'],
            [['to'], 'required'],
            ['from', 'match', 'pattern' => '/^[0-9]{11}$/i'],
            ['to', 'match', 'pattern' => '/^[0-9]{11}$/i'],
            [['from'], 'compare', 'compareAttribute' => 'to', 'operator'=>'<='],
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
            'from' => 'From',
            'to' => 'To',
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
    public function getLteNumberLists()
    {
        return $this->hasMany(LteNumberList::className(), ['lte_range_id' => 'id']);
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
     * This method can return all phones for special reseller
     * @return array|null
     * @author Mehran
     */
    public static function fetchNumberAsDropDownArray()
    {
        try {
            $result = [];
            $lte_numbers = self::find()
                ->joinWith(['lteNumberLists as lte_p'])
                ->where(['reseller_id' => Yii::$app->user->identity->reseller_id])
                ->all();

            if (!CommonRepository::checkAvailability($lte_numbers)) {
                return null;
            }

            foreach ($lte_numbers as $key => $value) {
                $ranje_number = $value['to'] - $value['from'] + 1;
                $start_number = $value['from'];
                for ($i = 0; $i < $ranje_number; $i++) {
                    if ($start_number <= $value['to']) {
                        $result[$start_number] = $start_number;
                    }
                    $start_number++;
                    $start_number = '0' . $start_number;
                }
            }

            return $result;
        } catch (Exception $exception) {
            return null;
        }

    }

    /**
     * It not completedly yet
     * @return array|null
     * @author Mehran
     */
    public static function checkPhoneAvailability($phone)
    {
        try {
            /**
             * This section declare the ranje of numbers according to resellers phone number ranje
             */
            $phone_number = self::find()
                ->where(['<=', 'from', $phone])
                ->andWhere(['>=', 'to', $phone])
                ->andWhere(['reseller_id' => Yii::$app->user->identity->reseller_id])
                //->andWhere(['not', ['parent_id' => null]])
                ->all();
            if ($phone_number == null) {
                return [
                    'statusCode' => 1,
                    'statusMessage' => "امکان تخصیص این شماره برای شماره وجود ندارد. شماره وارد شده در بازه مورد نظر وجود ندارد." ,
                ];
            }

            /**
             * This section check number is sold or not, if its sold user can not get that number
             */
            $lte_phone_purches = LteNumberList::find()
                ->where(['number' => $phone])
                ->one();
            if ($lte_phone_purches != null) {
                return [
                    'statusCode' => 2,
                    'statusMessage' => ' امکان تخصیص شماره وارد شده وجود ندارد. شماره از قبل تخصیص داده شده است.',
                ];
            }

            return [
                'statusCode' => 0,
                'statusMessage' => 'امکان اختصاص شماره ' . "<i><b>"  .  $phone . "</b></i>" .  ' وجود دارد.',
            ];
        } catch (Exception $exception) {
            return [
                'statusCode' => 3,
                'statusMessage' => 'مشکلی در استعلام شماره وجود دارد',
            ];
        }
    }
}
