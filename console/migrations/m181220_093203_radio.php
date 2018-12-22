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
            'pop_building_id' => $this->integer()->notNull(),
            
            'status' => $this->smallInteger()->notNull()->defaultValue(1),

            'creator_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),

            'deletor_id' => $this->integer(),
            'deleted_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-radio-pop_building_id',
            'service',
            'pop_building_id',
            'pop_building',
            'id',
            'CASCADE'
        );

    }

    public function down()
    {
        $this->dropTable('radio');
    }

}
