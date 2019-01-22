<?php

use app\models\db\Schema;
use yii\db\Migration;

/**
 * Class m181210_113357_CreateOfficeCharacterTbl
 */
class m181210_113357_CreateOfficeCharacterTbl extends Migration
{
    private $table_name = 'office_character';
    public function up()
    {
        $this->createTable($this->table_name,[
           'id' => $this->primaryKey(),
           'name' => $this->string(),
           'description' => $this->string(),
           'reseller_id' => $this->integer()
        ]);

        Schema::builder()->timestampBehavior($this->table_name);
        Schema::builder()->blameableBehavior($this->table_name);
    }

    public function down()
    {
        $this->dropTable($this->table_name);
    }

}
