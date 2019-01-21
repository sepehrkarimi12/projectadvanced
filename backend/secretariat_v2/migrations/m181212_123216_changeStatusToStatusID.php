<?php

use yii\db\Migration;

/**
 * Class m181212_123216_changeStatusToStatusID
 */
class m181212_123216_changeStatusToStatusID extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->dropForeignKey('fk_office_main_status_office_status','office_main');
        $this->renameColumn('office_main', 'status', 'status_id');
        $this->addForeignKey('fk_office_main_status_id_office_status', 'office_main', 'status_id', 'office_status', 'id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('fk_office_main_status_id_office_status','office_main');
        $this->renameColumn('office_main', 'status_id', 'status');
        $this->addForeignKey('fk_office_main_status_office_status', 'office_main', 'status', 'office_status', 'id', 'NO ACTION', 'NO ACTION');

    }
}
