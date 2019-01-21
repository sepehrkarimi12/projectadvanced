<?php

use yii\db\Migration;

/**
 * Class m181119_065011_AddForeignKeyToOfficeMainTable
 */
class m181119_065011_AddForeignKeyToOfficeMainTable extends Migration
{

    public function up()
    {
        $this->addForeignKey(
            'fk_office_main_office_type_id_office_type',
            '{{%office_main}}',
            'office_type_id',
            'office_type',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_main_access_level_id_office_access_level',
            '{{%office_main}}',
            'access_level_id',
            'office_access_level',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_main_priority_id_office_priority',
            '{{%office_main}}',
            'priority_id',
            'office_priority',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_main_category_id_office_category',
            '{{%office_main}}',
            'category_id',
            'office_category',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_main_office_type_id_office_type',
            '{{%office_main}}'
        );

        $this->dropForeignKey(
            'fk_office_main_access_level_id_office_access_level',
            '{{%office_main}}'
        );

        $this->dropForeignKey(
            'fk_office_main_priority_id_office_priority',
            '{{%office_main}}'
        );

        $this->dropForeignKey(
            'fk_office_main_category_id_office_category',
            '{{%office_main}}'
        );
    }

}
