<?php

use yii\db\Migration;

/**
 * Class m181114_043314_CreateOfficeInputTable
 */
class m181114_043314_CreateOfficeInputTable extends Migration
{
    public $table_name = 'office_input';

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'office_id' => $this->integer(),
            'number' => $this->string(),
            'receive_date' => $this->integer(),
            'deadline_date' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
