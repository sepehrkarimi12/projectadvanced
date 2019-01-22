<?php

use yii\db\Migration;

/**
 * Class m181112_152916_AddForeignKeyOfficeFolderTable
 */
class m181112_152916_AddForeignKeyOfficeFolderTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_folder_office_id_office_main',
            '{{%office_folder}}',
            'office_id',
            'office_main',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_folder_folder_office_folder',
            '{{%office_folder}}',
            'folder_id',
            'office_folder_type',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_folder_reseller_id_reseller',
            '{{%office_folder}}',
            'reseller_id',
            'reseller',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_folder_office_id_office_main',
            '{{%office_folder}}'
        );

        $this->dropForeignKey(
            'fk_office_folder_folder_office_folder',
            '{{%office_folder}}'
        );

        $this->dropForeignKey(
            'fk_office_folder_reseller_id_reseller',
            '{{%office_folder}}'
        );
    }
}
