<?php

use yii\db\Migration;

/**
 * Class m181220_093203_radio
 */
class m181220_093203_radio extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        
        $this->createTable('radio',[
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'model' => $this->string(100)->notNull(),
            'serial' => $this->string(100)->notNull(),
            'network_id' => $this->integer()->notNull(),
            
            'is_deleted' => $this->smallInteger()->defaultValue(0),

            'creator_id' => $this->integer(),
            'created_at' => $this->integer(),

            'deletor_id' => $this->integer(),
            'deleted_at' => $this->integer(),
        ]);

    }

    public function down()
    {
        $this->dropTable('radio');
    }

}
