<?php

use yii\db\Migration;

/**
 * Class m181114_044105_AddForeignKeyOfficeOutputTable
 */
class m181114_044105_AddForeignKeyOfficeOutputTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_output_office_id_office_main',
            '{{%office_output}}',
            'office_id',
            'office_main',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_output_office_id_office_main',
            '{{%office_output}}'
        );
    }
}
