<?php

use yii\db\Migration;

/**
 * Class m181210_115716_AddTwoNewColumnToofficeTypeTable
 */
class m181210_115716_AddTwoNewColumnToofficeTypeTable extends Migration
{
    private $table_name = 'office_type';
    public function up()
    {
        $this->addColumn($this->table_name, 'character_id', $this->integer());
        $this->addForeignKey(
            'fk_office_type_character_id_office_character',
            '{{%office_type}}',
            'character_id',
            'office_character',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addColumn($this->table_name, 'format_id', $this->integer());
        $this->addForeignKey(
            'fk_office_type_format_id_office_format',
            '{{%office_type}}',
            'format_id',
            'office_prefix_format',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_type_character_id_office_character',
            '{{%office_type}}'
        );
        $this->dropColumn($this->table_name, 'character_id', $this->integer());

        $this->dropForeignKey(
            'fk_office_type_format_id_office_format',
            '{{%office_type}}'
        );
        $this->dropColumn($this->table_name, 'format_id', $this->integer());
    }
}
