<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeMain;
use yii\db\Exception;
use mysqli_sql_exception;

/**
 * Check whether a letter is available or not
 * @author Noei
 */
class DeleteOfficeFolder extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        if (!$request->getService()->delete()){
            throw new mysqli_sql_exception();
        }

        else if ($this->successor != NULL) {
            $this->successor->handleRequest($request);
        }
    }
}