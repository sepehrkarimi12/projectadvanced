<?php

namespace app\modules\secretariat_v2\models;

use Yii;

/**
 * This is the model class for table "office_input".
 *
 * @property int $id
 * @property int $office_id
 * @property string $number
 *
 * @property OfficeMain $office
 */
class OfficeInput extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'office_input';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['office_id'], 'integer'],
            [['number'], 'string', 'max' => 255],
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
            'number' => 'Number',
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
