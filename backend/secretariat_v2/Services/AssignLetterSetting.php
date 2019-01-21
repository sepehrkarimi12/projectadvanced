<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use Exception;
use mysqli_sql_exception;
use Yii;

/**
 * this class is used for Office Assign letter Setting
 * @author Mehran
 */
class AssignLetterSetting extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        if ($request->getService()->assign_admin_id == null) {
            Throw new Exception("the Assign Admin Id has been Required");
        }

        if (!$request->getService()->save()) {
            throw new mysqli_sql_exception("U can not save this setting!");
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}