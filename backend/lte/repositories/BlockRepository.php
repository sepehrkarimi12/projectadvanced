<?php
namespace app\modules\lte\repositories;

use app\modules\lte\models\LteNumberRange;
use app\repositories\CommonRepository;
use Exception;

/**
 * This class has been used in BlockController
 * Class LteRepository
 * @package app\modules\phone_package\repositories
 * @author Mehran
 */
class BlockRepository
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
     * This method return array of phone number according to lte_ranje_id
     * @param $lte_id
     * @return bool|null
     * @author Mehran
     */
    public static function fetchLteRangeNumber($lte_id)
    {
        try {
            $lte_number = LteNumberRange::findOne($lte_id);
            if (!CommonRepository::checkAvailability($lte_number)) {
                return null;
            }

            $ranje_number = $lte_number['to'] - $lte_number['from'] + 1;
            $start_number = $lte_number['from'];
            for ($i = 0; $i < $ranje_number; $i++) {
                if ($start_number <= $lte_number['to']) {
                    $result[] = $start_number;
                }
                $start_number++;
                $start_number = '0' . $start_number;
            }

            return $result;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * This method get number range and then return status of then include their information
     * @param $lte_range_number
     * @return array|null
     * @author Mehran
     * @version 1
     */
    public static function fetchLteRangeNumberStatus($lte_range_number)
    {
        try {
            $result = [];
            foreach ($lte_range_number as $key => $value) {
                $result[$key]['number'] = $value;
                $result[$key]['status'] = 'مسدود';
                $result[$key]['buyer_id'] = 2258;
            }

            return $result;
        } catch (Exception $exception) {
            return null;
        }
    }

}
