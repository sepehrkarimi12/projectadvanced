<?php

use yii\db\Migration;

/**
 * Class m181114_101417_putDataToOfficePeopleTypeTable
 */
class m181114_101417_putDataToOfficePeopleTypeTable extends Migration
{
    public $table_name = 'office_people_type';

    public function up()
    {
        /*
         * 'درون سازمان' => 'درون سازمان','برون سازمان' => 'برون سازمان'
         */
        //insert
        $this->insert($this->table_name, ['id' => 1, 'name' => "درون سازمان", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
        $this->insert($this->table_name, ['id' => 2, 'name' => "برون سازمان", "created_at" => time(), "updated_at" => time(), "created_by" => 2258, "updated_by" => 2258]);
    }

    public function down()
    {
        $this->truncateTable($this->table_name);
    }
}
