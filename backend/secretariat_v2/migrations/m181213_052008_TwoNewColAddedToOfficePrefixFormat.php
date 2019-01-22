<?php

use yii\db\Migration;

/**
 * Class m181213_052008_TwoNewColAddedToOfficePrefixFormat
 */
class m181213_052008_TwoNewColAddedToOfficePrefixFormat extends Migration
{
    public $table_name = 'office_prefix_format';
    public function up()
    {
        $this->addColumn($this->table_name, 'reseller_id', $this->integer());
        $this->addForeignKey(
            'fk_office_prefix_format_reseller_id_reseller',
            '{{%office_prefix_format}}',
            'reseller_id',
            'reseller',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addColumn($this->table_name, 'format', $this->string());
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_prefix_format_reseller_id_reseller',
            '{{%office_prefix_format}}'
        );
        $this->dropColumn($this->table_name, 'reseller_id');

        $this->dropColumn($this->table_name, 'format');
    }
}
