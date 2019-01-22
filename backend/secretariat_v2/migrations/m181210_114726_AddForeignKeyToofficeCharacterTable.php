<?php

use yii\db\Migration;

/**
 * Class m181210_114726_AddForeignKeyToofficeCharacterTable
 */
class m181210_114726_AddForeignKeyToofficeCharacterTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_character_reseller_id_reseller',
            '{{%office_character}}',
            'reseller_id',
            'reseller',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_character_reseller_id_reseller',
            '{{%office_character}}'
        );
    }
}
