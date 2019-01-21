<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeAssign;
use app\modules\secretariat_v2\models\OfficeFolder;
use app\modules\secretariat_v2\models\OfficeFolderType;
use app\modules\secretariat_v2\models\OfficeMain;
use app\modules\secretariat_v2\models\OfficeRelation;
use app\modules\secretariat_v2\models\OfficeSetting;

/**
 * this class is responsible for saving office folders
 * @author Noei
 */
class SaveFolders extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        if ($request->getService()->folders != null) {
            foreach ($request->getService()->folders as $folder_id) {
                $office_folder = new OfficeFolder();
                $office_folder->save();
                $office_folder->link('office', $request->getService());
                $office_folder->link('folder', OfficeFolderType::findOne($folder_id));
            }
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}