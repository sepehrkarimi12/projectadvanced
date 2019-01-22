<?php
namespace app\modules\secretariat_v2\repositories;

use app\modules\secretariat_v2\models\OfficePeople;
use app\modules\secretariat_v2\Services\BeginTransaction;
use app\modules\secretariat_v2\Services\ChainOfResponsibilities;
use app\modules\secretariat_v2\Services\Request;
use app\modules\secretariat_v2\Services\SaveModel;
use Exception;

/**
 * This class used for ReceiverController
 * Class ReceiverRepository
 * @package app\modules\secretariat_v2\repositories
 * @author Mehran
 */
class ReceiverRepository
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
     * @param model $receiver
     * @return bool
     * @author mehran
     */
    public static function addReceiver($receiver)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new SaveModel());
            $chain->handleRequest(new Request($receiver));

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param model $receiver
     * @return bool
     * @author mehran
     */
    public static function updateReceiver($receiver)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new SaveModel());
            $chain->handleRequest(new Request($receiver));

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function showTree($people_type_id)
    {
        $organization = [];
        $count = null;
        $organizations = OfficePeople::find()
            ->where(['office_people_type_id' => $people_type_id])
            ->all();

        foreach ($organizations as $key => $value) {
            $organization[$key]['text'] = $value->name . ' ' . $value->family . '-' . $value->occuaption_name . '-' . $value->unit_name;
            $organization[$key]['id'] = $value->id;
            $organization[$key]['parent'] = 0;
            $organization[$key]['children'] = null;
            $count = $key;
        }

        $organization[$count + 1]['text'] = "بهار سامانه شرق";
        $organization[$count + 1]['id'] = 0;
        $organization[$count + 1]['parent'] = "#";

        return $organization;
    }
}
