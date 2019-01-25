<?php

use yii\db\Migration;

/**
 * Class m190122_043550_RefactorLteNumberPurchaseTable
 */
class m190122_043550_RefactorLteNumberPurchaseTable extends Migration
{
    public function up()
    {
        $this->dropForeignKey(
            'fk_lte_number_range_parent_id',
            'lte_number_range'
        );

        $this->dropColumn(
            'lte_number_range',
            'parent_id'
        );

    }

    public function down()
    {
        $this->addColumn(
            'lte_number_range',
            'parent_id',
            $this->integer()
        );

        $this->addForeignKey(
            'fk_lte_number_range_parent_id',
            'lte_number_range',
            'parent_id',
            'lte_number_range',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

    }
}
