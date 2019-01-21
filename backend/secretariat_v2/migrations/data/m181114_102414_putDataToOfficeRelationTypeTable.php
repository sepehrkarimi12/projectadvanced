<?php

use yii\db\Migration;

/**
 * Class m181114_102414_putDataToOfficeRelationTypeTable
 */
class m181114_102414_putDataToOfficeRelationTypeTable extends Migration
{
    public $table_name = 'office_relation_type';

    public function up()
    {
        /*
         * 'عطف', 'بازگشت', 'پیرو', 'جدید'
         */
        //insert
        $this->insert($this->table_name, ['id' => 1, 'name' => "عطف", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
        $this->insert($this->table_name, ['id' => 2, 'name' => "بازگشت", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
        $this->insert($this->table_name, ['id' => 3, 'name' => "پیرو", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
        $this->insert($this->table_name, ['id' => 4, 'name' => "جدید", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
    }

    public function down()
    {
        $this->truncateTable($this->table_name);
    }
}
