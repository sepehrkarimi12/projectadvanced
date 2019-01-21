<?php

namespace app\modules\secretariat_v2\models;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "office_output".
 *
 * @property int $id
 * @property int $office_id
 * @property int $is_old
 * @property int $is_signed
 * @property string $content
 * @property int $actionator_id
 * @property int $receiver_id
 *
 * @property OfficeMain $office
 */
class OfficeOutput extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'office_output';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['office_id', 'actionator_id', 'receiver_id', 'is_old', 'is_signed'], 'integer'],
            [['content'], 'safe'],
//            [['is_old', 'is_signed'], 'string', 'max' => 1],
            [['office_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeMain::className(), 'targetAttribute' => ['office_id' => 'id']],
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
            'is_old' => 'Is Old',
            'is_signed' => 'Is Signed',
            'content' => 'Content',
            'actionator_id' => 'Actionator ID',
            'receiver_id' => 'Receiver ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionator()
    {
        return $this->hasOne(OfficePeople::className(), ['id' => 'actionator_id']);
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
    public function getReceiver()
    {
        return $this->hasOne(OfficePeople::className(), ['id' => 'receiver_id']);
    }

    public function checkAvailability($property)
    {
        if ($this->$property == null) {
            Throw new NotFoundHttpException();
        }
    }
}