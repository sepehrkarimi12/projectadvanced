<?php

use yii\db\Migration;

/**
 * Class m190117_051348_product
 */
class m190117_051348_product extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('product',[
            'id' => $this->primaryKey(),
            'title' => $this->string(100),
            'price' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('product');
    }
}
