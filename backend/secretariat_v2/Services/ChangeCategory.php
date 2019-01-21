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
class ChangeCategory extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        if ($request->getService()->category_id != 1) {
            if ($request->getService()->secretariat_numbers == null) {
                Throw new Exception('The secretariat numbers is required!');
            }
            $secretariat_relation_type = OfficeRelationType::findOne(['name' => $request->getService()->category->name]);
            $find_family = OfficeRelation::find()->Where(['office_id' => $request->getService()->secretariat_numbers])->One();

            $office_relation = new OfficeRelation();
            $office_relation->link('office', $request->getService());
            $office_relation->link('officeReltype', $secretariat_relation_type);
            $office_relation->office_relation_id = $request->getService()->secretariat_numbers;
            if ($find_family == null) {
                $office_relation->family_id = $request->getService()->secretariat_numbers;
            } else {
                $office_relation->family_id = $find_family->family_id;
            }
            $office_relation->save();
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}