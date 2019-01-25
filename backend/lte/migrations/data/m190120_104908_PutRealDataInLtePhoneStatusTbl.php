<?php

use yii\db\Migration;

/**
 * Class m190120_104908_PutRealDataInLtePhoneStatusTbl
 */
class m190120_104908_PutRealDataInLtePhoneStatusTbl extends Migration
{
    public function up()
    {
        $this->insert('lte_number_status', [
            'id' => 1,
            'name' => 'فعال',
            'created_at' => time(),
            'updated_at' => time(),
            'created_by' => 2258,
            'updated_by' => 2258
        ]);
        $this->insert('lte_number_status', [
            'id' => 2,
            'name' => 'غیر فعال',
            'created_at' => time(),
            'updated_at' => time(),
            'created_by' => 2258,
            'updated_by' => 2258
        ]);
        $this->insert('lte_number_status', [
            'id' => 3,
            'name' => 'مسدود',
            'created_at' => time(),
            'updated_at' => time(),
            'created_by' => 2258,
            'updated_by' => 2258
        ]);
        $this->insert('lte_number_status', [
            'id' => 4,
            'name' => 'معلق',
            'created_at' => time(),
            'updated_at' => time(),
            'created_by' => 2258,
            'updated_by' => 2258
        ]);
    }

    public function down()
    {
        $this->truncateTable('lte_number_status');
    }
}
