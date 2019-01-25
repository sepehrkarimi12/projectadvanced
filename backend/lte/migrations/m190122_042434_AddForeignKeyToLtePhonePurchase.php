<?php

use yii\db\Migration;

/**
 * Class m190122_042434_AddForeignKeyToLtePhonePurchase
 */
class m190122_042434_AddForeignKeyToLtePhonePurchase extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_lte_number_purchase_lte_number_range_id',
            'lte_number_purchase',
            'lte_range_id',
            'lte_number_range',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_lte_number_purchase_lte_service_id',
            'lte_number_purchase',
            'lte_service_id',
            'lte',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_lte_number_purchase_reseller_id',
            'lte_number_purchase',
            'reseller_id',
            'reseller',
            'id',
            'NO ACTION',
            'NO ACTION');

        $this->addForeignKey(
            'fk_lte_number_purchase_status_id',
            'lte_number_purchase',
            'status_id',
            'lte_number_status',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_lte_number_purchase_flag_id',
            'lte_number_purchase',
            'flag_id',
            'lte_number_flag',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_lte_number_purchase_lte_number_range_id',
            'lte_number_purchase'
        );

        $this->dropForeignKey(
            'fk_lte_number_purchase_lte_service_id',
            'lte_number_purchase'
        );

        $this->dropForeignKey(
            'fk_lte_number_purchase_reseller_id',
            'lte_number_purchase'
        );

        $this->dropForeignKey(
            'fk_lte_number_purchase_status_id',
            'lte_number_purchase'
        );

        $this->dropForeignKey(
            'fk_lte_number_purchase_flag_id',
            'lte_number_purchase'
        );
    }
}
