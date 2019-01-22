<?php

use yii\db\Migration;

/**
 * Class m181114_043013_AddForeignKeyOfficePhotoTable
 */
class m181114_043013_AddForeignKeyOfficePhotoTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_photo_office_id_office_main',
            '{{%office_photo}}',
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
            'fk_office_photo_office_id_office_main',
            '{{%office_photo}}'
        );
    }
}
