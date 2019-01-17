<?php

use yii\db\Migration;

/**
 * Class m190117_052728_foreign_key_for_product_category
 */
class m190117_052728_foreign_key_for_product_category extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addForeignKey(
            'fk_category_product_from_category',
            'category_product',
            'category_id',
            'category',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_category_product_from_product',
            'category_product',
            'product_id',
            'product',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_category_product_from_category',
            'category_product'
        );

        $this->dropForeignKey(
            'fk_category_product_from_product',
            'category_product'
        );
    }

}
