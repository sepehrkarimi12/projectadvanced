<?php

use yii\db\Migration;

/**
 * Class m181220_051117_customer
 */
class m181220_051117_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('customer',[
            'id' => $this->primaryKey(),
            'fname' => $this->string(50)->notNull(),
            'lname' => $this->string(50)->notNull(),
            'address' => $this->string(200)->notNull(),
            'email' => $this->string(60),
            'phone' => $this->string(11)->notNull(),
            'mobile' => $this->string(11)->notNull(),

            //'isActive' => $this->ENUM('active','inActive')->notNull(),

            'creator_id' => $this->integer(),
            'created_at' => $this->datetime(),

            'deletor_id' => $this->integer(),
            'deleted_at' => $this->datetime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181220_051117_customer cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181220_051117_customer cannot be reverted.\n";

        return false;
    }
    */
}
