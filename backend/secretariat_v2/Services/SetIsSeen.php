<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeAssign;
use Yii;

/**
 * this class is a service responsible for setting is_seen
 * @author Noei
 */
class SetIsSeen extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        $secretariat_user = OfficeAssign::find()
            ->where(['office_id' => $request->getService()->id])
            ->andWhere(['user_id' => Yii::$app->user->identity->id])
            ->all();

        // a secretariat may be assigned to a user by more than one user
        foreach ($secretariat_user as $data){
            if($data->is_seen != 1){
                $data->is_seen = 1;
                if ($data->save()) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}