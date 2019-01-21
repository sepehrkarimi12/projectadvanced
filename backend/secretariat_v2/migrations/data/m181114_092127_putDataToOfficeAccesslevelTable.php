<?php

use yii\db\Migration;

/**
 * Class m181114_092127_putDataToOfficeAccesslevelTable
 */
class m181114_092127_putDataToOfficeAccesslevelTable extends Migration
{
    public $table_name = 'office_access_level';

    public function up()
    {
        /*
         * 'عادی' => 'عادی', 'محرمانه' => 'محرمانه', 'سری' => 'سری', 'فوق سری' => 'فوق سری'
         */
        //insert
        $this->insert($this->table_name, ['id' => 1, 'name' => "عادی", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
        $this->insert($this->table_name, ['id' => 2, 'name' => "محرمانه", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
        $this->insert($this->table_name, ['id' => 3, 'name' => "سری", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
        $this->insert($this->table_name, ['id' => 4, 'name' => "فوق سری", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
    }

    public function down()
    {
        $this->truncateTable($this->table_name);
    }
}
