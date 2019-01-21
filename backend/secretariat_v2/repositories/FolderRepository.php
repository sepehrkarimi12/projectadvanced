<?php
namespace app\modules\secretariat_v2\repositories;

use app\modules\secretariat_v2\Services\BeginTransaction;
use app\modules\secretariat_v2\Services\ChainOfResponsibilities;
use app\modules\secretariat_v2\Services\Request;
use app\modules\secretariat_v2\Services\SaveModel;
use Exception;

/**
 * This class used for folderController
 * Class FolderRepository
 * @package app\modules\secretariat_v2\repositories
 * @author Mehran
 */
class FolderRepository
{
    /**
     * Returns name of class.
     *
     * @return string
     */
    public static function className()
    {
        return get_called_class();
    }

    /**
     * @param model $folder
     * @return bool
     * @author mehran
     */
    public static function addFolder($folder)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new SaveModel());
            $chain->handleRequest(new Request($folder));

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param model $folder
     * @return bool
     * @author Mehran
     */
    public static function updateLetter($folder)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new SaveModel());
            $chain->handleRequest(new Request($folder));

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
