<?php

use app\models\db\Schema;
use yii\db\Migration;

/**
 * Class m181112_150054_CreateOfficeFolderTypeTable
 */
class m181112_150054_CreateOfficeFolderTypeTable extends Migration
{
    private $table_name = 'office_folder_type';

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'reseller_id' => $this->integer(),
        ]);

        Schema::builder()->timestampBehavior($this->table_name);
        Schema::builder()->blameableBehavior($this->table_name);
    }
    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
