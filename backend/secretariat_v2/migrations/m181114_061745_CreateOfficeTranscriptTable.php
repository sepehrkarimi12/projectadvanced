<?php

use app\models\db\Schema;
use yii\db\Migration;

/**
 * Class m181114_061745_CreateOfficeTranscriptTable
 */
class m181114_061745_CreateOfficeTranscriptTable extends Migration
{
    private $table_name = 'office_transcript';

    public function up()
    {
        $this->createTable($this->table_name, [
            'id' => $this->primaryKey(),
            'office_id' => $this->integer(),
            'office_people_id' => $this->integer()
        ]);

        Schema::builder()->timestampBehavior($this->table_name);
        Schema::builder()->blameableBehavior($this->table_name);
    }

    public function down()
    {
        $this->dropTable($this->table_name);
    }
}
