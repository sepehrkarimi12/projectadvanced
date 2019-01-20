<?php

use yii\db\Migration;

/**
 * Class m181220_083252_type
 */
class m181220_083252_servicetype extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('servicetype',[
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            
            'is_deleted' => $this->smallInteger()->defaultValue(0),
            'creator_username' => $this->string(100),

            'creator_id' => $this->integer(),
            'created_at' => $this->integer(),

            'deletor_id' => $this->integer(),
            'deleted_at' => $this->integer(),
        ]);

    }

    public function down()
    {
        $this->dropTable('servicetype');
    }
    
}
