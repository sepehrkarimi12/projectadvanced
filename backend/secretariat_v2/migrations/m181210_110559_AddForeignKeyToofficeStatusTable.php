<?php

use yii\db\Migration;

/**
 * Class m181210_110559_AddForeignKeyToofficeStatusTable
 */
class m181210_110559_AddForeignKeyToofficeStatusTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_main_status_office_status',
            '{{%office_main}}',
            'status',
            'office_status',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_main_status_office_status',
            '{{%office_main}}'
        );
    }
}
