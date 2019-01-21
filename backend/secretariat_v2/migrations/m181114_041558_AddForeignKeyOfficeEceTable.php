<?php

use yii\db\Migration;

/**
 * Class m181114_041558_AddForeignKeyOfficeEceTable
 */
class m181114_041558_AddForeignKeyOfficeEceTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_ece_office_id_office_main',
            '{{%office_ece}}',
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
            'fk_office_ece_office_id_office_main',
            '{{%office_ece}}'
        );
    }
}
