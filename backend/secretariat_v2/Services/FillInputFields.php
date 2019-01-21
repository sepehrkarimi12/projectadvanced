<?php

namespace app\modules\secretariat_v2\Services;

use app\models\CodeGenerator;
use app\models\Intldate;
use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeDeadline;
use app\modules\secretariat_v2\models\OfficeInput;
use app\modules\secretariat_v2\models\OfficeStatus;
use app\modules\secretariat_v2\models\OfficeType;
use app\modules\secretariat_v2\Services\Generators\PrefixGenerator;

/**
 * Saves data for input letter type(1)
 * @author Noei
 */
class FillInputFields extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        $office_input = new OfficeInput();

        $office_input->number = $request->getService()->secretariat_number;
        $office_input->office_id = $request->getService()->id;
        $office_input->save();

        $request->getService()->archive_number = CodeGenerator::generateInputLetterArchiveNumber();
        $request->getService()->archive_prefix = PrefixGenerator::getPrefix()."\\".OfficeType::findOne(1)->character->name."\\";

        $request->getService()->status_id = 1;
        $request->getService()->save();

        $office_input->link('office', $request->getService());

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}