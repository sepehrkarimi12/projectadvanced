<?php

use yii\db\Migration;

/**
 * Class m190117_095216_createLteNumberRangeTable
 */
class m190117_095216_createLteNumberRangeTable extends Migration
{
    public $table_name = 'lte_number_range';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table_name,[
            'id' => $this->primaryKey(),
            'from' => $this->string(),
            'to' => $this->string(),
            'reseller_id' => $this->integer(),
            'parent_id' => $this->integer()
        ]);

        \app\models\db\Schema::builder()->timestampBehavior($this->table_name);
        \app\models\db\Schema::builder()->blameableBehavior($this->table_name);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table_name);
    }
}
