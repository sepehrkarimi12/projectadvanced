<?php

use yii\db\Migration;

/**
 * Class m181114_080355_AddForeignKeyOfficeAssignTable
 */
class m181114_080355_AddForeignKeyOfficeAssignTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_assign_office_id_office_main',
            '{{%office_assign}}',
            'office_id',
            'office_main',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_assign_user_id_user',
            '{{%office_assign}}',
            'user_id',
            'user',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_assign_parent_id_user',
            '{{%office_assign}}',
            'parent_id',
            'user',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_assign_office_id_office_main',
            '{{%office_assign}}'
        );

        $this->dropForeignKey(
            'fk_office_assign_user_id_user',
            '{{%office_assign}}'
        );

        $this->dropForeignKey(
            'fk_office_assign_parent_id_user',
            '{{%office_assign}}'
        );
    }
}
