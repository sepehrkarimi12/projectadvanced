<?php

use app\models\db\Schema;
use yii\db\Migration;

/**
 * Class m181210_083725_CreateOfficeStatusTable
 */
class m181210_083725_CreateOfficeStatusTable extends Migration
{
    private $table_name = 'office_status';

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);

        Schema::builder()->timestampBehavior($this->table_name);
        Schema::builder()->blameableBehavior($this->table_name);
    }

    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
