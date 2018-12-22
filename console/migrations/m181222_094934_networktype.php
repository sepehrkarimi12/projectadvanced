<?php

use yii\db\Migration;

/**
 * Class m181222_094934_networktype
 */
class m181222_094934_networktype extends Migration
{

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('networktype',[
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull()->unique(),
            
            'is_deleted' => $this->smallInteger(),

            'creator_id' => $this->integer(),
            'created_at' => $this->integer(),

            'deletor_id' => $this->integer(),
            'deleted_at' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('networktype');
    }
    
}
