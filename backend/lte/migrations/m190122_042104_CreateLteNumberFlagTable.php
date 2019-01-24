<?php

use yii\db\Migration;

/**
 * Class m190122_042104_CreateLteNumberFlagTable
 */
class m190122_042104_CreateLteNumberFlagTable extends Migration
{
    private $table_name = 'lte_number_flag';
    public function up()
    {
        $this->createTable($this->table_name,[
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);

        \app\models\db\Schema::builder()->timestampBehavior($this->table_name);
        \app\models\db\Schema::builder()->blameableBehavior($this->table_name);
    }

    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
