<?php

use yii\db\Migration;

/**
 * Class m181119_090903_AlterOfficeOutputTable
 */
class m181119_090903_AlterOfficeOutputTable extends Migration
{
    public function up()
    {
        $this->alterColumn('office_output', 'content', $this->text());
    }

    public function down()
    {
        $this->alterColumn('office_output', 'content', $this->string());
    }
}
