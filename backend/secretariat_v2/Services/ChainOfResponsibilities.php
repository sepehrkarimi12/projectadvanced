<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeMain;

/**
 * Check whether a letter is available or not
 * @author Noei
 */
class ChainOfResponsibilities implements Handler
{

    private $successor;

    public function setSuccessor($nextService)
    {
        $this->successor = $nextService;
        return $nextService;
    }

    public function handleRequest($request)
    {
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}