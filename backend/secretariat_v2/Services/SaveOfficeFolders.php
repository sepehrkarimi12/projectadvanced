<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\manage\models\User;
use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeFolder;
use app\modules\secretariat_v2\models\OfficeFolderType;
use mysqli_sql_exception;

/**
 * this class is a service responsible for setting is_seen
 * @author Noei
 */
class SaveOfficeFolders extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        if ($request->getService()->folders != null) {
            foreach ($request->getService()->folders as $folder_id) {
                $office_t = new OfficeFolder();
                $office_t->office_id = $request->getService()->id;
                $office_t->folder_id = $folder_id;
                $office_t->save();
                /*if (OfficeFolder::findOne(['office_id' => $request->getService()->id, 'folder_id' => $folder_id,]) == null) {
                    $folder = OfficeFolderType::findOne($folder_id);
                    $request->getService()->link('officeFoldersType', $folder);
                    $request->getService()->save();
                    if ($request->getService()->link('officeFoldersType', $folder) != null) {
                        throw new mysqli_sql_exception('db exception');
                    }
                }*/
            }
        }


        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}