<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeAssign;
use app\modules\secretariat_v2\repositories\AssignRepository;
use app\modules\secretariat_v2\repositories\CommonRepository;
use Exception;
use Yii;

/**
 * this class is a service responsible for delete assign
 * @author Mehran
 */
class DeleteAssign extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        foreach ($request->getService()->childNodes as $childNodes) {
            $childNodes->depth--;
            $childNodes->parent_id = $request->getService()->parent_id;
            $childNodes->save();
        }
        $request->getService()->delete();

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}