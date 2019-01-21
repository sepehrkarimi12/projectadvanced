<?php

use yii\db\Migration;

/**
 * Class m181114_092551_putDataToOfficePriorityTable
 */
class m181114_092551_putDataToOfficePriorityTable extends Migration
{
    public $table_name = 'office_priority';

    public function up()
    {
        /*
         * 'عادی' => 'عادی', 'فوری' => 'فوری', 'خیلی فوری' => 'خیلی فوری'
         */
        //insert
        $this->insert($this->table_name, ['id' => 1, 'name' => "عادی", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
        $this->insert($this->table_name, ['id' => 2, 'name' => "فوری", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
        $this->insert($this->table_name, ['id' => 3, 'name' => "خیلی فوری", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
    }

    public function down()
    {
        $this->truncateTable($this->table_name);
    }
}
