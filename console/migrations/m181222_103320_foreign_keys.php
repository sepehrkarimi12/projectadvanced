<?php

use yii\db\Migration;

/**
 * Class m181222_103320_foreign_keys
 */
class m181222_103320_foreign_keys extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'FK_customer_1_service_n_',
            'service',
            'customer_id',
            'customer',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'FK_customer_1_comment_n_',
            'comment',
            'customer_id',
            'customer',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'FK_servicetype_1_service_n_',
            'service',
            'type_id',
            'servicetype',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'FK_network_1_service_n_',
            'service',
            'network_id',
            'network',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'FK_networktype_1_network_n_',
            'network',
            'type_id',
            'networktype',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'FK_network_1_radio_n_',
            'radio',
            'network_id',
            'network',
            'id',
            'CASCADE',
            'NO ACTION'
        );

    }

    public function down()
    {

    }
}
