<?php

namespace app\modules\secretariat_v2\models;

use Yii;

/**
 * This is the model class for table "office_deadline".
 *
 * @property int $id
 * @property int $office_main_input_id
 * @property int $dead_line
 *
 * @property OfficeInput $officeMainInput
 */
class OfficeDeadline extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'office_deadline';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['office_main_input_id', 'dead_line'], 'integer'],
            [['office_main_input_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeInput::className(), 'targetAttribute' => ['office_main_input_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'office_main_input_id' => 'Office Main Input ID',
            'dead_line' => 'Dead Line',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeMainInput()
    {
        return $this->hasOne(OfficeInput::className(), ['id' => 'office_main_input_id']);
    }
}
