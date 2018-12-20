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
            'type_id' => $this->integer()->notNull(),
            'address' => $this->string(200)->notNull(),
            'customer_id' => $this->integer()->notNull(),
            'ppoe_username' => $this->string(100)->notNull(),
            'ppoe_password' => $this->string(100)->notNull(),

            'isActive' => $this->smallIntegeer()->notNull(),

            'creator_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),

            'deletor_id' => $this->integer(),
            'deleted_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-service-customer_id',
            'service',
            'customer_id'
        );

        $this->addForeignKey(
            'fk-service-customer_id',
            'service',
            'customer_id',
            'customer',
            'id',
            'CASCADE'
        );

    }

    public function down()
    {
        $this->dropTable('service');
    }
    
}
