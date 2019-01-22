<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeAttachment;
use yii\base\Exception;
use yii\web\UploadedFile;

/**
 * this class is responsible  uploading photos in secretariat attachment uploads
 * it uploads the pictures set on the attachments property on model then set events which sets uploads by OfficeAttachmentUploads() class and fills it before creation on insert
 * in order to use change the directory I suggest you to set a directory parameter on model
 * then modify this class to dynamicly choose directory.
 * @author Noei
 */
class SaveAttachmentUploads extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {

        $request->getService()->attachments->photos = UploadedFile::getInstances($request->getService()->attachments, 'photos');
        if ($request->getService()->attachments->photos != null) {

            if ($request->getService()->attachments->validate()) {
                foreach ($request->getService()->attachments->photos as $image) {

                    $file_path_attachment = 'uploads/secretariat_attachment/' . date('Y-m-d-H-i-s') . "_" . $request->getService()->id . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999)) . '.' . $image->extension;
                    $secretariat_attachment = new OfficeAttachment();
                    $secretariat_attachment->office_id = $request->getService()->id;
                    $secretariat_attachment->created_at = time();
                    $secretariat_attachment->path = $file_path_attachment;
                    $secretariat_attachment->save();
                    $image->saveAs($file_path_attachment);

                }
            } else {
                throw new Exception('attachments validation');
            }
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}