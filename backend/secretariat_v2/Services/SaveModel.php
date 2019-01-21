<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeAssign;
use Exception;
use Yii;

/**
 * this class is for save every model came to this class
 * @author Mwhran
 */
class SaveModel extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        if (!$request->getService()->save()) {
            Throw new Exception("U can not saeve this model!");
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}