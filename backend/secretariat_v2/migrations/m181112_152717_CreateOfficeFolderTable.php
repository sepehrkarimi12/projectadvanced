<?php

use app\models\db\Schema;
use yii\db\Migration;

/**
 * Class m181112_152717_CreateOfficeFolderTable
 */
class m181112_152717_CreateOfficeFolderTable extends Migration
{
    private $table_name = 'office_folder';

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'office_id' => $this->integer(),
            'folder_id' => $this->integer(),
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
