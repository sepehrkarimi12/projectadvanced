<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeAssign;
use app\modules\secretariat_v2\models\OfficeMain;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Check if the user has the permission to access the letter
 * @author Noei
 */
class CheckAssignPermission  extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        if (
            !OfficeAssign::find()->where(['office_id' => $request->getService()->id, 'user_id' => Yii::$app->user->identity->id])->exists() &&
            !Yii::$app->user->can('SecretariatListLettersPerm')){
            throw new ForbiddenHttpException();
        }

        else if ($request->getService()->status_id == 2) {
            throw new ForbiddenHttpException();
        }

        else if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}