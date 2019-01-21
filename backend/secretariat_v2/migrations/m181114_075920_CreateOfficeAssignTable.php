<?php

use app\models\db\Schema;
use yii\db\Migration;

/**
 * Class m181114_075920_CreateOfficeAssignTable
 */
class m181114_075920_CreateOfficeAssignTable extends Migration
{
    private $table_name = 'office_assign';

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'office_id' => $this->integer(),
            'user_id' => $this->integer(),
            'parent_id' => $this->integer(),
            'paraph' => $this->text(),
            'is_seen' => $this->integer(),
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
