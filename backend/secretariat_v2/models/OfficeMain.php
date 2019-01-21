<?php

namespace app\modules\secretariat_v2\models;

use app\models\Intldate;
use app\models\traits\Archives;
use app\models\traits\Exists;
use app\models\traits\SoftDeletes;
use app\modules\manage\models\User;
use app\modules\secretariat_v2\models\uploads\OfficeUpload;
use app\modules\secretariat_v2\models\uploads\OfficeUploadAttachment;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "office_main".
 *
 * @property int $id
 * @property string $archive_prefix
 * @property string $archive_number
 * @property int $date
 * @property string $title
 * @property string $description
 * @property int $status_id
 * @property int $is_archived
 * @property int $sender_id
 * @property int $office_type_id
 * @property int $access_level_id
 * @property int $priority_id
 * @property int $category_id
 * @property int $reseller_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $isDeleted
 * @property int $deletedAt
 * @property int $deletedBy
 * @property int $category
 * @property int $letter_date
 *
 * @property array $uploads
 * @property array $attachments
 *
 * @property OfficeAssign[] $officeAssigns
 * @property OfficeAttachment[] $officeAttachments
 * @property OfficeEce[] $officeEces
 * @property OfficeFolder[] $officeFolders
 * @property OfficeInput[] $officeInputs
 * @property OfficeAccessLevel $accessLevel
 * @property User $createdBy
 * @property User $deletedBy0
 * @property OfficeType $officeType
 * @property OfficePriority $priority
 * @property User $updatedBy
 * @property OfficeOutput[] $officeOutputs
 * @property OfficePhoto[] $officePhotos
 * @property OfficeRelation[] $officeRelations
 * @property OfficeRelation[] $officeRelations0
 * @property OfficeRelation[] $officeRelations1
 * @property OfficeTranscript[] $officeTranscripts
 */
class OfficeMain extends \yii\db\ActiveRecord
{
    use Exists;
    use SoftDeletes;
    use Archives;

    const EVENT_NEW_OFFICE = 'new-office';


    public $uploads;
    public $attachments;


    public $folder_type_id;

    public $assigned_person;
    public $folders;
    public $transcripts; //
    public $actionator_id;
    public $receiver_id;
    public $secretariat_numbers;
    public $secretariat_number;
    public $is_old;
    public $transcrigpt;
    public $start_day;
    public $start_month;
    public $start_year;


    /**
     * Convert input letter_date from persian date to timestamp
     * @param bool $insert
     * @return bool
     * @author Noei
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->isNewRecord) {
            $this->letter_date = Intldate::get()->persianToTimestampMd($this->letter_date);
        }
        return true;
    }

    /**
     * for setting $uploads property and is called on creation of model when it is new record
     * @author Noei
     */
    public function setPicturesProperty()
    {
        $this->uploads = new OfficeUpload();
    }

    /**
     * for setting $attachmenets property and is called on creation of model when it is new record
     * @author Noei
     */
    public function setAttachementProperty()
    {
        $this->attachments = new OfficeUploadAttachment();
    }

    /**
     * setting $uploads parameter on inserting new record
     * this calls setPicturesProperty method
     * @author Noei
     */
    public function init()
    {
        $this->on(self::EVENT_NEW_OFFICE, [$this, 'setPicturesProperty']);
        $this->on(self::EVENT_NEW_OFFICE, [$this, 'setAttachementProperty']);
        $this->trigger(self::EVENT_NEW_OFFICE);
    }

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
//                    ActiveRecord::EVENT_BEFORE_UPDATE => 'reseller_id',
                ],
                'value' => function ($event) {
                    return Yii::$app->user->identity->reseller_id;
                },
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'isDeleted',
                ],
                'value' => function ($event) {
                    return 0;
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'office_main';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['secretariat_number', 'transcript', 'assigned_person', 'folders', 'actionator_id', 'receiver_id', 'secretariat_numbers', 'secretariat_number', 'is_old'], 'safe'],
            [['start_day', 'start_month', 'start_year'], 'safe'],
            [['photos', 'attachments'], 'safe'],
            [['uploads'], 'required'],
            [['status_id', 'date', 'sender_id', 'office_type_id', 'access_level_id', 'priority_id', 'category_id', 'reseller_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'isDeleted', 'deletedAt', 'deletedBy', 'is_archived'], 'integer'],
            [['title', 'description', 'letter_date'], 'string'],
            [['title', 'letter_date'], 'required'],
            [['archive_prefix', 'archive_number'], 'string', 'max' => 255],
            [['access_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeAccessLevel::className(), 'targetAttribute' => ['access_level_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deletedBy'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['deletedBy' => 'id']],
            [['office_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeType::className(), 'targetAttribute' => ['office_type_id' => 'id']],
            [['priority_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficePriority::className(), 'targetAttribute' => ['priority_id' => 'id']],
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
            'archive_prefix' => 'Archive Prefix',
            'archive_number' => 'Archive Number',
            'date' => 'Date',
            'title' => 'Title',
            'description' => 'Description',
            'status_id' => 'Status Id',
            'is_archived' => 'Is Archive',
            'sender_id' => 'Sender ID',
            'office_type_id' => 'Office Type ID',
            'access_level_id' => 'Access Level ID',
            'priority_id' => 'Priority ID',
            'category_id' => 'Category ID',
            'reseller_id' => 'Reseller ID',
            'uploads' => 'Uploads',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'isDeleted' => 'Is Deleted',
            'deletedAt' => 'Deleted At',
            'deletedBy' => 'Deleted By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeAssigns()
    {
        return $this->hasMany(OfficeAssign::className(), ['office_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeAttachments()
    {
        $attachments = $this->hasMany(OfficeAttachment::className(), ['office_id' => 'id']);
        isset($attachments) ? $attachments : null;
        return $attachments;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeEces()
    {
        return $this->hasMany(OfficeEce::className(), ['office_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeFolders()
    {
        return $this->hasMany(OfficeFolder::className(), ['office_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeFoldersType()
    {
        return $this->hasMany(OfficeFolderType::className(), ['id' => 'folder_id'])
            ->viaTable('office_folder', ['office_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeInput()
    {
        return $this->hasOne(OfficeInput::className(), ['office_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessLevel()
    {
        return $this->hasOne(OfficeAccessLevel::className(), ['id' => 'access_level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(OfficeCategory::className(), ['id' => 'category_id']);
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
    public function getDeletedByUser()
    {
        return $this->hasOne(User::className(), ['id' => 'deletedBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeType()
    {
        return $this->hasOne(OfficeType::className(), ['id' => 'office_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriority()
    {
        return $this->hasOne(OfficePriority::className(), ['id' => 'priority_id']);
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
    public function getOfficeOutputs()
    {
        return $this->hasOne(OfficeOutput::className(), ['office_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficePhotos()
    {
        $photos = $this->hasMany(OfficePhoto::className(), ['office_id' => 'id']);

        isset($photos) ? $photos : null;
        return $photos;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeFamilyRelations()
    {
        return $this->hasMany(OfficeRelation::className(), ['family_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeRelations()
    {
        return $this->hasOne(OfficeRelation::className(), ['office_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeOfficeRelRelations()
    {
        return $this->hasMany(OfficeRelation::className(), ['office_relation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeTranscripts()
    {
        return $this->hasMany(OfficeTranscript::className(), ['office_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(OfficePeople::className(), ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeStatus()
    {
        return $this->hasOne(OfficeStatus::className(), ['id' => 'status_id']);
    }

    public static function exists($archive_number)
    {
        if (self::find()->where(['archive_number' => $archive_number])->exists()) {
            return true;
        } else {
            return false;
        }
    }

    public static function assignPerson()
    {
        $assigned_person = 842;
        $exist_person = OfficeSetting::findOne(1);

        if ($exist_person != null) {
            if ($exist_person->assign_admin_id != null) {
                $assigned_person = $exist_person->assign_admin_id;
            }
        }

        return $assigned_person;
    }

    public static function managePeople()
    {
        $people_id = 2;
        $exist_person = OfficeSetting::findOne(1);

        if ($exist_person != null) {
            if ($exist_person->people_admin_id != null) {
                $people_id = $exist_person->people_admin_id;
            }
        }

        return $people_id;
    }


    /**
     * this method returns array of office numbers which have the accept status
     * this can get used as kartik select2 data
     * @return array
     * @author Noei
     */
    public static function getOfficeNumbers()
    {
        return ArrayHelper::map(OfficeMain::find()
            ->Where(['reseller_id' => Yii::$app->user->identity->reseller_id])
            ->andWhere(['status_id' => 1])
            ->All(), 'id', function($model) { return $model->archive_prefix.$model->archive_number; } );
    }

}
