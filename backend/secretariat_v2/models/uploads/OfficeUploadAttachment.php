<?php

namespace app\modules\secretariat_v2\models\uploads;

use app\modules\secretariat_v2\models\OfficeAttachment;

class OfficeUploadAttachment extends OfficeAttachment
{
    public $photos;

    public function rules()
    {
        return
            [
                [['photos'], 'file', 'extensions' => 'png, jpg, jpeg, pdf, docx', 'maxSize' => 3024000, 'maxFiles' => '10'],
            ];
    }
}
