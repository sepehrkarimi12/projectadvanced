<?php

use yii\db\Migration;

/**
 * Class m181220_092631_pop_building
 */
class m181220_092631_pop_building extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('pop_building',[
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'address' => $this->string(200)->notNull(),
            'ip_address' => $this->string(15)->notNull(),
            
            'isActive' => $this->smallIntegeer()->notNull(),

            'creator_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),

            'deletor_id' => $this->integer(),
            'deleted_at' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('pop_building');
    }
    
}
