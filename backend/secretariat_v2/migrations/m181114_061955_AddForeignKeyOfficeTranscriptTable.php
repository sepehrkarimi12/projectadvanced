<?php

use yii\db\Migration;

/**
 * Class m181114_061955_AddForeignKeyOfficeTranscriptTable
 */
class m181114_061955_AddForeignKeyOfficeTranscriptTable extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk_office_transcript_office_id_office_main',
            '{{%office_transcript}}',
            'office_id',
            'office_main',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->addForeignKey(
            'fk_office_relation_office_people_id_office_main',
            '{{%office_transcript}}',
            'office_people_id',
            'office_people',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_office_transcript_office_id_office_main',
            '{{%office_transcript}}'
        );

        $this->dropForeignKey(
            'fk_office_relation_office_people_id_office_main',
            '{{%office_transcript}}'
        );
    }
}
