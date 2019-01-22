<?php

use yii\db\Migration;

/**
 * Class m181119_064525_AddForeignKeyToPishgamanBukhttbl
 */
class m181119_064525_AlterOfficeTableCol extends Migration
{
    public function up()
    {
        $this->alterColumn('office_main', 'office_type_id', $this->integer());
        $this->alterColumn('office_main', 'access_level_id', $this->integer());
        $this->alterColumn('office_main', 'priority_id', $this->integer());
    }

    public function down()
    {
        $this->alterColumn('office_main', 'office_type_id', $this->string());
        $this->alterColumn('office_main', 'access_level_id', $this->string());
        $this->alterColumn('office_main', 'priority_id', $this->string());
    }
}
