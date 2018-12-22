<?php

use yii\db\Migration;

/**
 * Class m181222_082820_comment
 */
class m181222_082820_comment extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('comment',[
            'id' => $this->primaryKey(),
            'text' => $this->text()->notNull(),
            'file' => $this->string(100),
            'customer_id' => $this->integer()->notNull(),

            'status' => $this->smallInteger()->notNull()->defaultValue(1),

            'creator_id' => $this->integer(),
            'created_at' => $this->integer(),

            'deletor_id' => $this->integer(),
            'deleted_at' => $this->integer(),
        ]);

    }

    public function down()
    {
        $this->dropTable('comment');
    }
    
}
