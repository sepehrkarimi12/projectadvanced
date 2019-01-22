<?php

use yii\db\Migration;

/**
 * Class m181210_084728_putDataToOfficeStatusTable
 */
class m181210_084728_putDataToOfficeStatusTable extends Migration
{
    private $table_name = 'office_status';

    public function up()
    {
        $this->insert($this->table_name,['id' => 1, 'name' => 'accept', "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
        $this->insert($this->table_name,['id' => 2, 'name' => 'pending', "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
    }

    public function down()
    {
        $this->truncateTable($this->table_name);
    }
}
