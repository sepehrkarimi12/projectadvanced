<?php

use yii\db\Migration;

/**
 * Class m181112_141505_AddForeignKeyOfficePeople
 */
class m181112_141505_AddForeignKeyOfficePeople extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_people_reseller_id_reseller',
            '{{%office_people}}',
            'reseller_id',
            'reseller',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_people_office_people_type_id',
            '{{%office_people}}',
            'office_people_type_id',
            'office_people_type',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_people_reseller_id_reseller',
            '{{%office_people}}'
        );

        $this->dropForeignKey(
            'fk_office_people_office_people_type_id',
            '{{%office_people}}'
        );
    }

}
