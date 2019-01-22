<?php

use yii\db\Migration;

/**
 * Class m181217_072004_changeDeptAndIsSeenDefaultValue
 */
class m181217_072004_changeDeptAndIsSeenDefaultValue extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('office_assign', 'is_seen', $this->boolean()->defaultValue(0));
        $this->alterColumn('office_assign', 'depth', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('office_assign', 'is_seen', $this->integer());
        $this->alterColumn('office_assign', 'depth', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181217_072004_changeDeptAndIsSeenDefaultValue cannot be reverted.\n";

        return false;
    }
    */
}
