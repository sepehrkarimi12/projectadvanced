<?php

use app\models\db\Schema;
use yii\db\Migration;

/**
 * Class m181114_045227_CreateOfficeRelationTable
 */
class m181114_045227_CreateOfficeRelationTable extends Migration
{
    private $table_name = 'office_relation';

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'office_id' => $this->integer(),
            'office_relation_id' => $this->integer(),
            'office_reltype_id' => $this->integer(),
            'family_id' => $this->integer(),
            'depth' => $this->integer(),
        ]);

        Schema::builder()->timestampBehavior($this->table_name);
        Schema::builder()->blameableBehavior($this->table_name);
    }

    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
