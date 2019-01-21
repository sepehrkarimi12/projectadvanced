<?php

use yii\db\Migration;

/**
 * Class m181121_135730_AddForeignKeyToofficeDeadLineTbl
 */
class m181121_135730_AddForeignKeyToofficeDeadLineTbl extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_deadline_office_main_input_office_input',
            '{{%office_deadline}}',
            'office_main_input_id',
            'office_input',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_deadline_office_main_input_office_input',
            '{{%office_deadline}}'
        );
    }
}
