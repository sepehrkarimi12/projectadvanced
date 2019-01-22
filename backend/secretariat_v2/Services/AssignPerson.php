<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;

/**
 * this class is for Confirmation output Letter for letter register by user
 * @author Mehran
 */
class AssignPerson extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        $request->getService()->assigned_person = 842;

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}