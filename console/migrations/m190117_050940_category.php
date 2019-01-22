<?php

use yii\db\Migration;

/**
 * Class m190117_050940_category
 */
class m190117_050940_category extends Migration
{
    public function up()
    {
        $this->createTable('category',[
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('category');
    }

}
