<?php

namespace frontend\models;

use yii\helpers\ArrayHelper;
use common\models\User;
use Yii;
use common\components\timeTrait;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property string $text
 * @property string $file
 * @property int $customer_id
 * @property int $is_deleted
 * @property int $creator_id
 * @property int $created_at
 * @property int $deletor_id
 * @property int $deleted_at
 *
 * @property User $creator
 * @property User $deletor
 * @property Customer $customer
 */
class Comment extends \yii\db\ActiveRecord
{
    // for customizing save and delete
    use \common\components\timeTrait;

    public $imageFile;
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'customer_id'], 'required'],
            [['text'], 'string'],
            [['customer_id', 'is_deleted', 'creator_id', 'created_at', 'deletor_id', 'deleted_at'], 'integer'],
            [['file'], 'string', 'max' => 100],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
            [['deletor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['deletor_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['imageFile'], 'file' , 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Text'),
            'file' => Yii::t('app', 'File'),
            'customer_id' => Yii::t('app', 'Customer Name'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'creator_id' => Yii::t('app', 'Creator Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'deletor_id' => Yii::t('app', 'Deletor ID'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletor()
    {
        return $this->hasOne(User::className(), ['id' => 'deletor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    public function upload()
    {
        $address = null;
        if($this->imageFile){
        $address = 'uploads/' . time() . '.' . $this->imageFile->extension;
        $this->imageFile->saveAs($address, false);
        }
        return $address;
    }

}
