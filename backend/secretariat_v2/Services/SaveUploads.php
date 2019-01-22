<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficePhoto;
use yii\web\UploadedFile;

/**
 * this class is responsible  uploading photos in secretariat uploads
 * it uploads the pictures set on the uploads property on model then set events which sets uploads by OfficeUploads() class and fills it before creation on insert
 * in order to use change the directory I suggest you to set a directory parameter on model
 * then modify this class to dynamicly choose directory.
 * @author Noei
 */
class SaveUploads extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {

        $request->getService()->uploads->photo = UploadedFile::getInstances($request->getService()->uploads, 'photo');

        foreach ($request->getService()->uploads->photo as $photo) {
            $file_path = 'uploads/input_secretariat_photos/' . date('Y-m-d-H-i-s') . "_" . $request->getService()->id . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999)) . '.' . $photo->extension;
            $office_photo = new OfficePhoto();
            $office_photo->office_id = $request->getService()->id;
            $office_photo->path = $file_path;
            $office_photo->save();
            $photo->saveAs($file_path);
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}