<?php

use yii\db\Migration;

/**
 * Class m181121_133100_CreateofficeDeadLineTbl
 */
class m181121_133100_CreateofficeDeadLineTbl extends Migration
{
    private $table_name = 'office_deadline';

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'office_main_input_id' => $this->integer(),
            'dead_line' => $this->integer(),
        ]);

    }
    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
