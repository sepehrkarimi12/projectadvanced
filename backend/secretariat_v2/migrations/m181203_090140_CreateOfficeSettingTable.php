<?php

use app\models\db\Schema;
use yii\db\Migration;

/**
 * Class m181203_090140_CreateOfficeSettingTable
 */
class m181203_090140_CreateOfficeSettingTable extends Migration
{
    private $table_name = 'office_setting';

    public function up()
    {
        $this->createTable($this->table_name, [
           'id' => $this->primaryKey(),
           'assign_admin_id' => $this->integer(),
           'people_admin_id' => $this->integer()
        ]);

        Schema::builder()->timestampBehavior($this->table_name);
        Schema::builder()->blameableBehavior($this->table_name);
    }

    public function down()
    {
        $this->dropTable($this->table_name);
    }

}
