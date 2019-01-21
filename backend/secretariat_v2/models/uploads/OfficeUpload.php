<?php

namespace app\modules\secretariat_v2\models\uploads;

use app\modules\secretariat_v2\models\OfficePhoto;
use Yii;

class OfficeUpload extends OfficePhoto
{
    public $photo;

    public function rules()
    {
        return
            [
                [['photo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, docx, pdf', 'maxSize' => 3024000, 'maxFiles' => '10'],
            ];
    }
}
