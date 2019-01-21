<?php

use app\models\db\Schema;
use yii\db\Migration;

/**
 * Class m181112_140539_CreateOfficePeopleTable
 */
class m181112_140539_CreateOfficePeopleTable extends Migration
{
    private $table_name = 'office_people';

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'company_name' => $this->string(),
            'occuaption_name' => $this->string(),
            'unit_name' => $this->string(),
            'family' => $this->string(),
            'reseller_id' => $this->integer(),
            'tel' => $this->string(),
            'office_people_type_id' => $this->integer(),
        ]);

        Schema::builder()->timestampBehavior($this->table_name);
        Schema::builder()->blameableBehavior($this->table_name);
    }
    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
