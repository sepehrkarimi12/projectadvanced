<?php

use yii\db\Migration;

/**
 * Class m181220_080920_service
 */
class m181220_080920_service extends Migration
{

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('service',[
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'customer_id' => $this->integer()->notNull(),
            'type_id' => $this->integer()->notNull(),
            'network_id' => $this->integer()->notNull(),
            'address' => $this->string(200)->notNull(),
            'ppoe_username' => $this->string(100)->notNull(),
            'ppoe_password' => $this->string(100)->notNull(),

            'is_deleted' => $this->smallInteger()->defaultValue(0),

            'creator_id' => $this->integer(),
            'created_at' => $this->integer(),

            'deletor_id' => $this->integer(),
            'deleted_at' => $this->integer(),
        ]);

    }

    public function down()
    {
        $this->dropTable('service');
    }
    
}
