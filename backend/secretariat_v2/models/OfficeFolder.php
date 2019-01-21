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
use yii\db\Exception;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "office_folder".
 *
 * @property int $id
 * @property int $office_id
 * @property int $folder_id
 * @property int $reseller_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property OfficeFolderType $folder
 * @property OfficeMain $office
 * @property Reseller $reseller
 * @property User $updatedBy
 */
class OfficeFolder extends \yii\db\ActiveRecord
{

    public $folder_type_id;

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
        return 'office_folder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['folder_type_id'], 'safe'],
            [['office_id', 'folder_id', 'reseller_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['folder_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeFolderType::className(), 'targetAttribute' => ['folder_id' => 'id']],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeMain::className(), 'targetAttribute' => ['office_id' => 'id']],
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
            'office_id' => 'Office ID',
            'folder_id' => 'Folder ID',
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
    public function getFolder()
    {
        return $this->hasOne(OfficeFolderType::className(), ['id' => 'folder_id']);
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

    public static function fetchOfficeFolder($office_id, $folder_id) {
        $office_folder = OfficeFolder::findOne([
            'office_id' => $office_id,
            'folder_id' => $folder_id,
        ]);

        if ($office_folder != null) {
            return $office_folder;
        } else {
            throw new NotFoundHttpException();
        }
    }
}
