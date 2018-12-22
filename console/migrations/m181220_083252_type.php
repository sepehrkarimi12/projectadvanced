<?php

use yii\db\Migration;

/**
 * Class m181220_083252_type
 */
class m181220_083252_type extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('type',[
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull()->unique(),
            
            'status' => $this->smallInteger()->notNull()->defaultValue(1),

            'creator_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),

            'deletor_id' => $this->integer(),
            'deleted_at' => $this->integer()->notNull(),
        ]);

        // $this->addForeignKey(
        //     'fk-type-id',
        //     'type',
        //     'id',
        //     'service',
        //     'id',
        //     'CASCADE'
        // );

    }

    public function down()
    {
        $this->dropTable('type');
    }
    
}
