<?php

use yii\db\Migration;

/**
 * Class m190117_051925_category_product
 */
class m190117_051925_category_product extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('category_product',[
           'id' => $this->primaryKey(),
           'category_id' => $this->integer(),
           'product_id' => $this->integer(),
        ]);

    }

    public function down()
    {
        $this->dropTable('category_product');
    }

}
