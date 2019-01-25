<?php

use yii\db\Migration;

/**
 * Class m190122_041218_CreateLteNumberPurchaseTable
 */
class m190122_041218_CreateLteNumberPurchaseTable extends Migration
{
    public $table_name = 'lte_number_purchase';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable($this->table_name);
        $this->createTable($this->table_name,[
            'id' => $this->primaryKey(),
            'number' => $this->string(),
            'lte_range_id' => $this->integer(),
            'lte_service_id' => $this->integer(),
            'reseller_id' => $this->integer(),
            'flag_id' => $this->integer(),
            'status_id' => $this->integer()
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
