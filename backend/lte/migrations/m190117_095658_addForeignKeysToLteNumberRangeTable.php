<?php

use yii\db\Migration;

/**
 * Class m190117_095658_addForeignKeysToLteNumberRangeTable
 */
class m190117_095658_addForeignKeysToLteNumberRangeTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk_lte_number_range_reseller_id', 'lte_number_range', 'reseller_id', 'reseller', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_lte_number_range_parent_id', 'lte_number_range', 'parent_id', 'lte_number_range', 'id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_lte_number_range_reseller_id', 'lte_number_range');
        $this->dropForeignKey('fk_lte_number_range_parent_id', 'lte_number_range');
    }
}
