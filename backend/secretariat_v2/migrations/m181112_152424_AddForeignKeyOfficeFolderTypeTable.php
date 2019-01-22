<?php

use yii\db\Migration;

/**
 * Class m181112_152424_AddForeignKeyOfficeFolderTypeTable
 */
class m181112_152424_AddForeignKeyOfficeFolderTypeTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_folder_type_reseller_id_reseller',
            '{{%office_folder_type}}',
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
            'fk_office_folder_type_reseller_id_reseller',
            '{{%office_folder_type}}'
        );
    }
}
