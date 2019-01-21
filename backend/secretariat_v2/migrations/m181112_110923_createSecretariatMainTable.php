<?php

use yii\db\Migration;
use app\models\db\Schema;

/**
 * Class m181112_110923_createSecretariatMainTable
 */
class m181112_110923_createSecretariatMainTable extends Migration
{

    private $table_name = 'office_main';

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'archive_prefix' => $this->string(),
            'archive_number' => $this->string(),
            'date' => $this->integer(),
            'title' => $this->text(),
            'description' => $this->text(),
            'status' => $this->string(),
            'archive_flag' => $this->boolean(),
            'sender_id' => $this->integer(),
            'office_type_id' => $this->string(),
            'access_level_id' => $this->string(),
            'priority_id' => $this->string(),
            'category_id' => $this->integer(),
            'reseller_id' => $this->integer()
        ]);

        Schema::builder()->timestampBehavior($this->table_name);
        Schema::builder()->blameableBehavior($this->table_name);
        Schema::builder()->softDelete($this->table_name);
    }
    public function down()
    {
        $this->dropTable($this->table_name);
    }

}