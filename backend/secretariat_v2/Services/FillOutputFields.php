<?php

namespace app\modules\secretariat_v2\Services;

use app\models\CodeGenerator;
use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeOutput;
use app\modules\secretariat_v2\models\OfficeStatus;

/**
 * Saves data for output letter type(1)
 * @author Noei
 */
class FillOutputFields extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {

        $office_output = new OfficeOutput();
        $office_output->receiver_id = $request->getService()->receiver_id;
        $office_output->actionator_id = $request->getService()->actionator_id;
        $office_output->office_id = $request->getService()->id;

        $office_output->save();
        $request->getService()->archive_number = CodeGenerator::generateInputLetterArchiveNumber();
        $request->getService()->save();
        $office_output->link('office', $request->getService());
        $request->getService()->link('officeStatus', OfficeStatus::findOne(1));

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}