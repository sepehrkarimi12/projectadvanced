<?php

use yii\db\Migration;

/**
 * Class m181114_092940_putDataToOfficeTypeTable
 */
class m181114_092940_putDataToOfficeTypeTable extends Migration
{
    public $table_name = 'office_type';

    public function up()
    {
        /*
         * 'ورودی' => 'ورودی', 'خروجی' => 'خروجی'
         */
        //insert
        $this->insert($this->table_name, ['id' => 1, 'name' => "ورودی", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
        $this->insert($this->table_name, ['id' => 2, 'name' => "خروجی", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
    }

    public function down()
    {
        $this->truncateTable($this->table_name);
    }
}
