<?php

use yii\db\Migration;

/**
 * Class m181203_092101_AddForeignKeyToofficeSettingTable
 */
class m181203_092101_AddForeignKeyToofficeSettingTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_setting_assign_admin_id_user',
            '{{%office_setting}}',
            'assign_admin_id',
            'user',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_setting_people_admin_id_office_people',
            '{{%office_setting}}',
            'people_admin_id',
            'office_people',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_setting_assign_admin_id_user',
            '{{%office_setting}}'
        );

        $this->dropForeignKey(
            'fk_office_setting_people_admin_id_office_people',
            '{{%office_setting}}'
        );
    }
}
