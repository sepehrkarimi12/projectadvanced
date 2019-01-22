<?php

namespace app\modules\secretariat_v2\models;

use app\models\interfaces\KartikInterface;
use app\models\traits\KartikHelper;
use app\modules\manage\models\User;
use nox\helpers\ArrayHelper;
use Yii;

/**
* This is the model class for table "office_type".
 *
 * @property int $id
* @property string $name
* @property int $created_at
* @property int $updated_at
* @property int $created_by
* @property int $updated_by
* @property int $character_id
* @property int $format_id
*
 * @property OfficeMain[] $officeMains
* @property OfficeCharacter $character
* @property User $createdBy
* @property OfficePrefixFormat $format
* @property User $updatedBy
*/
class OfficeType extends \yii\db\ActiveRecord
{
    use KartikHelper;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'office_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'character_id', 'format_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['character_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficeCharacter::className(), 'targetAttribute' => ['character_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['format_id'], 'exist', 'skipOnError' => true, 'targetClass' => OfficePrefixFormat::className(), 'targetAttribute' => ['format_id' => 'id']],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'character_id' => 'Character ID',
            'format_id' => 'Format ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfficeMains()
    {
        return $this->hasMany(OfficeMain::className(), ['office_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCharacter()
    {
        return $this->hasOne(OfficeCharacter::className(), ['id' => 'character_id']);
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
    public function getFormat()
    {
        return $this->hasOne(OfficePrefixFormat::className(), ['id' => 'format_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
