<?php

use yii\db\Migration;

/**
 * Class m181114_040936_CreateOfficeEceTable
 */
class m181114_040936_CreateOfficeEceTable extends Migration
{
    public $table_name = "office_ece";

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'office_id' => $this->integer(),
            'sender_organization' => $this->string(),
            'sender_department' => $this->string(),
            'sender_position' => $this->string(),
            'sender_name' => $this->string(),
            'sender_code' => $this->string(),
            'receiver_organization' => $this->string(),
            'receiver_department' => $this->string(),
            'receiver_position' => $this->string(),
            'receiver_name' => $this->string(),
            'receiver_code' => $this->string(),
            'path' => $this->string(),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
