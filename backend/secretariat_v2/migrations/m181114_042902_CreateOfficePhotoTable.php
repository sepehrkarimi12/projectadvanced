<?php

use app\models\db\Schema;
use yii\db\Migration;

/**
 * Class m181114_042902_CreateOfficePhotoTable
 */
class m181114_042902_CreateOfficePhotoTable extends Migration
{
    private $table_name = 'office_photo';

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'office_id' => $this->integer(),
            'path' => $this->string(),
        ]);

        Schema::builder()->timestampBehavior($this->table_name);
        Schema::builder()->blameableBehavior($this->table_name);
    }

    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
