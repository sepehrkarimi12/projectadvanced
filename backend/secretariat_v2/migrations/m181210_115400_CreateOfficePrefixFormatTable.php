<?php

use app\models\db\Schema;
use yii\db\Migration;

/**
 * Class m181210_115400_CreateOfficePrefixFormatTable
 */
class m181210_115400_CreateOfficePrefixFormatTable extends Migration
{
    private $table_name = 'office_prefix_format';
    public function up()
    {
        $this->createTable($this->table_name,[
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->string(),
        ]);

        Schema::builder()->timestampBehavior($this->table_name);
        Schema::builder()->blameableBehavior($this->table_name);
    }

    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
