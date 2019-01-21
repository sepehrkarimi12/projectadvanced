<?php

use yii\db\Migration;

/**
 * Class m181114_044042_CreateOfficeOutputTable
 */
class m181114_044042_CreateOfficeOutputTable extends Migration
{
    public $table_name = 'office_output';

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'office_id' => $this->integer(),
            'is_old' => $this->boolean(),
            'is_signed' => $this->boolean(),
            'content' => $this->string(),
            'actionator_id' => $this->integer(),
            'receiver_id' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
