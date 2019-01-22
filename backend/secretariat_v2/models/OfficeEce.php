<?php

namespace app\modules\secretariat_v2\models;

use Yii;

/**
 * This is the model class for table "office_ece".
 *
 * @property int $id
 * @property int $office_id
 * @property string $sender_organization
 * @property string $sender_department
 * @property string $sender_position
 * @property string $sender_name
 * @property string $sender_code
 * @property string $receiver_organization
 * @property string $receiver_department
 * @property string $receiver_position
 * @property string $receiver_name
 * @property string $receiver_code
 * @property string $path
 *
 * @property OfficeMain $office
 */
class OfficeEce extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'office_ece';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['office_id'], 'integer'],
            [['sender_organization', 'sender_department', 'sender_position', 'sender_name', 'sender_code', 'receiver_organization', 'receiver_department', 'receiver_position', 'receiver_name', 'receiver_code', 'path'], 'string', 'max' => 255],
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
            'sender_organization' => 'Sender Organization',
            'sender_department' => 'Sender Department',
            'sender_position' => 'Sender Position',
            'sender_name' => 'Sender Name',
            'sender_code' => 'Sender Code',
            'receiver_organization' => 'Receiver Organization',
            'receiver_department' => 'Receiver Department',
            'receiver_position' => 'Receiver Position',
            'receiver_name' => 'Receiver Name',
            'receiver_code' => 'Receiver Code',
            'path' => 'Path',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffice()
    {
        return $this->hasOne(OfficeMain::className(), ['id' => 'office_id']);
    }
}
