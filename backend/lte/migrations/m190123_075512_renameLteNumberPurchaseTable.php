<?php

use yii\db\Migration;

/**
 * Class m190123_075512_renameLteNumberPurchaseTable
 */
class m190123_075512_renameLteNumberPurchaseTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('lte_number_purchase', 'lte_number_list');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->renameTable('lte_number_list', 'lte_number_purchase');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190123_075512_renameLteNumberPurchaseTable cannot be reverted.\n";

        return false;
    }
    */
}
