<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeAssign;
use app\modules\secretariat_v2\models\OfficeMain;
use app\modules\secretariat_v2\models\OfficeRelation;
use app\modules\secretariat_v2\models\OfficeSetting;

/**
 * this class is responsible for setting assings on letter creation
 * @author Noei
 */
class SetAssigns extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        $head_branch = OfficeSetting::find()->where(['reseller_id' => \Yii::$app->user->identity->reseller_id])->one();

        $office_assign = new OfficeAssign();

        $office_assign->save();


        $office_assign->link('user', $head_branch->assignAdmin);

        $office_assign->link('office', $request->getService());

        if ($request->getService()->category->id != 1) {
            $find_family = OfficeRelation::findOne(['office_id' => $request->getService()->secretariat_numbers]);

            $office_relation = new OfficeRelation();
            $office_relation->link('office', $request->getService());
            $office_relation->link('officeRelation', OfficeMain::findOne($request->getService()->secretariat_numbers));
            $office_relation->link('officeReltype', $request->getService()->category);

            if ($find_family == null) {
                $office_relation->family_id = $request->getService()->secretariat_numbers;
            } else {
                $office_relation->family_id = $find_family->family_id;
            }
            $office_relation->depth = 0;
            if ($office_relation->save()) {
                pd("sdf");
            } else {
                pd($office_relation->errors);
            }
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}