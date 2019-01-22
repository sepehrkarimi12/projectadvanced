<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeRelation;
use app\modules\secretariat_v2\models\OfficeRelationType;
use Exception;

/**
 * this class is a service responsible for change category of special letter
 * @author Mehran
 */
class deleteCategory extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        $office_relation = OfficeRelation::find()
            ->where(['office_id' => $request->getService()->id])
            ->all();

        foreach ($office_relation as $value) {
            $value->delete();
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}