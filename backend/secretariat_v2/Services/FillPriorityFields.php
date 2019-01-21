<?php

namespace app\modules\secretariat_v2\Services;

use app\models\CodeGenerator;
use app\models\Intldate;
use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeDeadline;
use app\modules\secretariat_v2\models\OfficeInput;
use app\modules\secretariat_v2\models\OfficeStatus;

/**
 * Saves data for priority input letter type(1)
 * this class should be used after a saved input class in chain
 * @author Noei
 */
class FillPriorityFields extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        $office_deadline = new OfficeDeadline();
        $office_input = $request->getService()->officeInput;

        if ($office_input == null) {
            throw new \mysqli_sql_exception();
        }

        if ($request->getService()->priority_id != 1) {
            $office_deadline->office_main_input_id = $office_input->id;
            $office_deadline->dead_line = Intldate::get()->createTimestampFromPersianDropdown($request->getService()->start_year, $request->getService()->start_month, $request->getService()->start_day);
            $office_deadline->save();
            $office_deadline->link('officeMainInput', $office_input);
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}