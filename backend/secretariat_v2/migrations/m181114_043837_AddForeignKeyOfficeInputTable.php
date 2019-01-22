<?php

use yii\db\Migration;

/**
 * Class m181114_043837_AddForeignKeyOfficeInputTable
 */
class m181114_043837_AddForeignKeyOfficeInputTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_input_office_id_office_main',
            '{{%office_input}}',
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
            'fk_office_input_office_id_office_main',
            '{{%office_input}}'
        );
    }
}
