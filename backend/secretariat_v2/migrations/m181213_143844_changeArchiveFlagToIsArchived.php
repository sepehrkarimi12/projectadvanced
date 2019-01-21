<?php

use yii\db\Migration;

/**
 * Class m181213_143844_changeArchiveFlagToIsArchived
 */
class m181213_143844_changeArchiveFlagToIsArchived extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('office_main', 'archive_flag', 'is_archived');
        $this->alterColumn('office_main', 'is_archived', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('office_main', 'is_archived', 'archive_flag');
        $this->alterColumn('office_main', 'archive_flag', $this->integer());
    }

}
