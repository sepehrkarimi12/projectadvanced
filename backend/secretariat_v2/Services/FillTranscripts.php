<?php

namespace app\modules\secretariat_v2\Services;

use app\models\CodeGenerator;
use app\models\Intldate;
use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeDeadline;
use app\modules\secretariat_v2\models\OfficeInput;
use app\modules\secretariat_v2\models\OfficeStatus;
use app\modules\secretariat_v2\models\OfficeTranscript;

/**
 * this service handles setting office transcripts
 * the workflow is first delete all transcripts then fill data based on kartik selection
 * @author Noei
 */
class FillTranscripts extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        // clearing all transcripts
        if ($request->getService()->officeTranscripts != null) {
            OfficeTranscript::deleteAll(['office_id' => $request->getService()->id]);
        }

        // save kartik selected transcripts
        $transcript = $request->getService()->transcript;
        if ($transcript != null) {
            foreach ($transcript as $key => $value) {
                $new_secretariat_transcript = new OfficeTranscript();
                $new_secretariat_transcript->office_people_id = $value;
                $new_secretariat_transcript->office_id = $request->getService()->id;
                $new_secretariat_transcript->save();
            }
        }


        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}