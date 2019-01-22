<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\manage\models\User;
use app\modules\manage\models\UserSignature;
use app\modules\secretariat_v2\Interfaces\Handler;
use Exception;
use Yii;

/**
 * this class is for Confirmation output Letter for letter register by user
 * @author Mehran
 */
class ConfirmationLetter extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        $user_id = Yii::$app->user->identity->id;
        $user = User::findOne($user_id);

        if ($user == null) {
            Throw new Exception("User Not found");
        }

        $surname = $user->profile->name . "  " . $user->profile->family;

        $user_signature = UserSignature::find()
            ->where(['user_id' => $user_id])
            ->one();

        $signarue_image = null;
        if ($user_signature != null) {
            $signarue_image =  '<img src="/' . $user_signature->path . ' " style="width:200px; heigth: 100px;" alt="Eror404"/>';
        }


        $office_output = $request->getService()->officeOutputs;

        $content = str_replace('<div style="font-weight: bold; font-size: 12pt; text-align: right;">باتشکر</div>',
            '<div style="font-weight: bold; font-size: 12pt; text-align: center;">
                        <p style="font-weight: bold; font-size: 12pt;"> ' . $signarue_image . ' </p>
                        <p style="font-weight: bold; font-size: 12pt;">باتشکر</p>
                        <p style="font-weight: bold; font-size: 12pt;">' . $surname . '</p>
                     </div>',
            $office_output->content);
        $office_output->content = $content;
        $office_output->is_signed = 1;
        $office_output->save();

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}