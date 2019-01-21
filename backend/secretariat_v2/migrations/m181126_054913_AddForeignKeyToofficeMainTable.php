<?php

use yii\db\Migration;

/**
 * Class m181126_054913_AddForeignKeyToofficeMainTable
 */
class m181126_054913_AddForeignKeyToofficeMainTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_main_sender_id_office_people',
            '{{%office_main}}',
            'sender_id',
            'office_people',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_main_sender_id_office_people',
            '{{%office_main}}'
        );
    }
}
