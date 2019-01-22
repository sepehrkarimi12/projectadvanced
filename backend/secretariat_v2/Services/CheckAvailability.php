<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeMain;

/**
 * Check whether a letter is available or not
 * @author Noei
 */
class CheckAvailability extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        if ($request->getService() == NULL) {
            Throw new \Exception("Model not found!");
        }

        else if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}