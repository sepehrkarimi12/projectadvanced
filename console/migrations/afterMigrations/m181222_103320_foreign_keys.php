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

        // to user table
        $this->addForeignKey(
            'FK_customer creator',
            'customer',
            'creator_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'FK_customer deletor',
            'customer',
            'deletor_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'FK_service creator',
            'service',
            'creator_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'FK_service deletor',
            'service',
            'deletor_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'FK_servicetype creator',
            'servicetype',
            'creator_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'FK_servicetype deletor',
            'servicetype',
            'deletor_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'FK_network creator',
            'network',
            'creator_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'FK_network deletor',
            'network',
            'deletor_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'FK_radio creator',
            'radio',
            'creator_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'FK_radio deletor',
            'radio',
            'deletor_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'FK_comment creator',
            'comment',
            'creator_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'FK_comment deletor',
            'comment',
            'deletor_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'FK_networktype creator',
            'networktype',
            'creator_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'FK_networktype deletor',
            'networktype',
            'deletor_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );

        $this->addForeignKey(
            'FK_authassignment_1_user_n_',
            'auth_assignment',
            'user_id',
            'user',
            'id',
            'RESTRICT',
            'RESTRICT'
        );


    }

    public function down()
    {

    }
}
