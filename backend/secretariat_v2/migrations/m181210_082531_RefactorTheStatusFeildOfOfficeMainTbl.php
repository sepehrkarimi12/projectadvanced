<?php

use yii\db\Migration;

/**
 * Class m181210_082531_RefactorTheStatusFeildOfOfficeMainTbl
 */
class m181210_082531_RefactorTheStatusFeildOfOfficeMainTbl extends Migration
{
    public $tbl_name = 'office_main';

    public function up()
    {
        $this->update(
            $this->tbl_name,
            ['status' => null]
        );

        $this->alterColumn($this->tbl_name, 'status', $this->integer());
    }

    public function down()
    {
        $this->alterColumn($this->tbl_name, 'status', $this->string());
    }
}
