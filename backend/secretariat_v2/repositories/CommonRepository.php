<?php
namespace app\modules\secretariat_v2\repositories;

use app\modules\secretariat_v2\Services\BeginTransaction;
use app\modules\secretariat_v2\Services\ChainOfResponsibilities;
use app\modules\secretariat_v2\Services\CheckAvailability;
use app\modules\secretariat_v2\Services\Request;
use app\modules\secretariat_v2\Services\SaveModel;
use Exception;

/**
 * In This class, we write the common method used in all over in this Module
 * Class CommonRepository
 * @package app\modules\secretariat_v2\repositories
 * @author mehran
 */
class CommonRepository
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
     * this method for check model is exist or not from officeMain table
     * @param model $office
     * @return bool
     * @author Mehran
     */
    public static function checkAvailability($model)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new CheckAvailability());
            $chain->handleRequest(new Request($model));

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * this method for save every model came
     * @param $model
     * @return bool
     * @author Mehran
     */
    public static function saveModel($model)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new CheckAvailability())->setSuccessor(new SaveModel());
            $chain->handleRequest(new Request($model));

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }


}
