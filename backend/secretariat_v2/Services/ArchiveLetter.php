<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeAssign;
use Exception;
use Yii;

/**
 * this class is a service responsible for setting is_seen
 * @author Noei
 */
class ArchiveLetter extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        $request->getService()->is_archived = 1;
        if (!$request->getService()->save()) {
            Throw new Exception("U can not save letter!");
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}