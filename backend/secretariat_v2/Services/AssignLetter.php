<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeAssign;
use app\modules\secretariat_v2\repositories\AssignRepository;
use app\modules\secretariat_v2\repositories\CommonRepository;
use Exception;
use Yii;

/**
 * this class is a service responsible for add new assign letter
 * @author Mehran
 */
class AssignLetter extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        if (!$request->getService()->exists('user_id')) {
            Throw new Exception('The receiver is Required');
        }

        $request->getService()->link('parent', Yii::$app->user->identity);
        $request->getService()->depth = AssignRepository::treeAssignDepth($request->getService());
        $request->getService()->save();


        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}