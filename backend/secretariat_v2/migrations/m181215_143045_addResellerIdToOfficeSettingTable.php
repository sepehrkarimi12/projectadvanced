<?php

use yii\db\Migration;

/**
 * Class m181215_143045_addResellerIdToOfficeSettingTable
 */
class m181215_143045_addResellerIdToOfficeSettingTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('office_setting', 'reseller_id', $this->integer());
        $this->addForeignKey('fk_office_setting_reseller_id_reseller', 'office_setting', 'reseller_id', 'reseller', 'id','NO ACTION', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('office_setting', 'reseller_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181215_143045_addResellerIdToOfficeSettingTable cannot be reverted.\n";

        return false;
    }
    */
}
