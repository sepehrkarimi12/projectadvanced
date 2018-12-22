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
    public function Up()
    {
        $this->createTable('customer',[
            'id' => $this->primaryKey(),
            'fname' => $this->string(50)->notNull(),
            'lname' => $this->string(50)->notNull(),
            'address' => $this->string(200)->notNull(),
            'email' => $this->string(60),
            'phone' => $this->string(11)->notNull(),
            'mobile' => $this->string(11)->notNull(),

            'status' => $this->smallInteger()->notNull()->defaultValue(1),

            'creator_id' => $this->integer(),
            'created_at' => $this->integer(),

            'deletor_id' => $this->integer(),
            'deleted_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
        $this->dropTable('customer');
    }

}
