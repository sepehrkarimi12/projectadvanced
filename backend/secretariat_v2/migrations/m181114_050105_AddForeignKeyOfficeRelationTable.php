<?php

use yii\db\Migration;

/**
 * Class m181114_050105_AddForeignKeyOfficeRelationTable
 */
class m181114_050105_AddForeignKeyOfficeRelationTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_relation_office_id_office_main',
            '{{%office_relation}}',
            'office_id',
            'office_main',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_relation_office_relation_id_office_main',
            '{{%office_relation}}',
            'office_relation_id',
            'office_main',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_relation_office_reltype_id_office_relation_type',
            '{{%office_relation}}',
            'office_reltype_id',
            'office_relation_type',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_relation_family_id_office_main',
            '{{%office_relation}}',
            'family_id',
            'office_main',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_relation_office_id_office_main',
            '{{%office_relation}}'
        );

        $this->dropForeignKey(
            'fk_office_relation_office_relation_id_office_main',
            '{{%office_relation}}'
        );

        $this->dropForeignKey(
            'fk_office_relation_office_reltype_id_office_relation_type',
            '{{%office_relation}}'
        );

        $this->dropForeignKey(
            'fk_office_relation_family_id_office_main',
            '{{%office_relation}}'
        );
    }
}
