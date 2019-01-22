<?php

use yii\db\Migration;

/**
 * Class m181126_042027_AddForeignKeyToofficeOutputLetter
 */
class m181126_042027_AddForeignKeyToofficeOutputLetter extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_output_actionator_id_office_people',
            '{{%office_output}}',
            'actionator_id',
            'office_people',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_output_receiver_id_office_people',
            '{{%office_output}}',
            'receiver_id',
            'office_people',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_output_actionator_id_office_people',
            '{{%office_output}}'
        );

        $this->dropForeignKey(
            'fk_office_output_receiver_id_office_people',
            '{{%office_output}}'
        );
    }
}
