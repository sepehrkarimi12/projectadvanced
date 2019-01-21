<?php

use yii\db\Migration;

/**
 * Class m181121_124550_RefactorOfficemainTableAndOfficeInput
 */
class m181121_124550_RefactorOfficemainTableAndOfficeInput extends Migration
{
    public function up()
    {
        //Office Main
        $this->addColumn('office_main', 'letter_date', $this->integer());

        //Office input
        $this->dropColumn('office_input', 'receive_date');
        $this->dropColumn('office_input', 'deadline_date');
    }

    public function down()
    {
        //Office Main
        $this->dropColumn('office_main', 'letter_date');

        //Office input
        $this->addColumn('office_input', 'receive_date', $this->integer());
        $this->addColumn('office_input', 'deadline_date', $this->integer());
    }

}
