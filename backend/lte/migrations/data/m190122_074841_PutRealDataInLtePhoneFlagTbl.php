<?php

use yii\db\Migration;

/**
 * Class m190122_074841_PutRealDataInLtePhoneFlagTbl
 */
class m190122_074841_PutRealDataInLtePhoneFlagTbl extends Migration
{
    public function up()
    {
        $this->insert('lte_number_flag', [
            'id' => 1,
            'name' => 'آزاد',
            'created_at' => time(),
            'updated_at' => time(),
            'created_by' => 2258,
            'updated_by' => 2258
        ]);
        $this->insert('lte_number_flag', [
            'id' => 2,
            'name' => 'تخصیص داده شده',
            'created_at' => time(),
            'updated_at' => time(),
            'created_by' => 2258,
            'updated_by' => 2258
        ]);
    }

    public function down()
    {
        $this->truncateTable('lte_number_flag');
    }
}
