<?php
namespace app\modules\secretariat_v2\repositories;


use app\modules\secretariat_v2\models\OfficeSetting;
use app\modules\secretariat_v2\Services\AssignLetterSetting;
use app\modules\secretariat_v2\Services\BeginTransaction;
use app\modules\secretariat_v2\Services\ChainOfResponsibilities;
use app\modules\secretariat_v2\Services\CheckAvailability;
use app\modules\secretariat_v2\Services\PeopleLetterSetting;
use app\modules\secretariat_v2\Services\Request;
use app\modules\secretariat_v2\Services\SaveModel;
use Exception;

/**
 * This class use in SettingController
 * Class SettingRepository
 * @package app\modules\secretariat_v2\repositories
 * @author mehran
 */
class SettingRepository
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
     * @return OfficeSetting|null
     * @throws Exception
     * @author Mehran
     */
    public static function officeSetting()
    {
        try {
            $office_setting = OfficeSetting::findOne(1);
            if ($office_setting == null) {
                $office_setting = new OfficeSetting();
            }

            return $office_setting;
        } catch (Exception $e) {
            Throw new Exception("There is some problem!");
        }
    }

    /**
     * @param model $setting
     * @return bool
     * @author Mehran
     */
    public static function officeAssignLetterSetting($setting)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new AssignLetterSetting());
            $chain->handleRequest(new Request($setting));

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param model $setting
     * @return bool
     * @author Mehran
     */
    public static function peopleLetterSetting($setting)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new PeopleLetterSetting());
            $chain->handleRequest(new Request($setting));

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
