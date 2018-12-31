<?php

use yii\db\Migration;

/**
 * Class m181220_092631_pop_building
 */
class m181220_092631_network extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('network',[
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'address' => $this->string(200)->notNull(),
            'type_id' => $this->integer()->notNull(),
            'ip_address' => $this->string(15),
            
            'is_deleted' => $this->smallInteger()->defaultValue(0),

            'creator_id' => $this->integer(),
            'created_at' => $this->integer(),

            'deletor_id' => $this->integer(),
            'deleted_at' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('network');
    }
    
}
