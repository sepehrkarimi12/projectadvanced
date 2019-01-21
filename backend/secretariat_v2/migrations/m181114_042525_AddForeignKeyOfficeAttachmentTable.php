<?php

use yii\db\Migration;

/**
 * Class m181114_042525_AddForeignKeyOfficeAttachmentTable
 */
class m181114_042525_AddForeignKeyOfficeAttachmentTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_attachment_office_id_office_main',
            '{{%office_attachment}}',
            'office_id',
            'office_main',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_attachment_office_id_office_main',
            '{{%office_attachment}}'
        );
    }
}
