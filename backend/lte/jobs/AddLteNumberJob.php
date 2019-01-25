<?php
namespace app\modules\lte\jobs;

use app\modules\lte\models\LteNumberList;
use app\modules\lte\repositories\BlockRepository;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\RetryableJobInterface;


class AddLteNumberJob extends BaseObject implements JobInterface,RetryableJobInterface
{
    /**
     * @var $mobile_number
     */
    public $mobile_number;
    /**
     * @var $reseller_id
     */
    public $reseller_id;
    /**
     * @var $lte_range_id
     */
    public $lte_range_id;
    /**
     * @var $flag_id
     */
    public $flag_id;
    /**
     * @var $status_id
     */
    public $status_id;
    /**
     * @var $status_id
     */
    public $ttr = 2;


    public function execute($queue)
    {
        $data["number"] = $this->mobile_number;
        $data["reseller_id"] = $this->reseller_id;
        $data["lte_range_id"] = $this->lte_range_id;
        $data["flag_id"] = $this->flag_id;
        $data["status_id"] = $this->status_id;

        $lte = new LteNumberList($data);
        if ($lte->save()) {
            echo "The " . $this->mobile_number . " has been added secussfully" . "\n";
        } else {
            echo "No" . "\n";
        }
    }

    /**
     * Max time for anything job handling is 15 minutes
     * @return float|int
     * @author Mehran
     */
    public function getTtr()
    {
        return $this->ttr * 60;
    }

    /**
     * This method called when *****
     * @param $attempt
     * @param $error
     * @return bool
     * @author Mehran
     */
    public function canRetry($attempt, $error)
    {
        return ($attempt < 5) && ($error instanceof TemporaryException);
    }
}