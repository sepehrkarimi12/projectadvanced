<?php
namespace app\modules\secretariat_v2\repositories;

use app\modules\manage\models\User;
use app\modules\secretariat_v2\models\OfficeAssign;
use app\modules\secretariat_v2\Services\AssignLetter;
use app\modules\secretariat_v2\Services\BeginTransaction;
use app\modules\secretariat_v2\Services\ChainOfResponsibilities;
use app\modules\secretariat_v2\Services\CheckAvailability;
use app\modules\secretariat_v2\Services\DeleteAssign;
use app\modules\secretariat_v2\Services\Request;
use app\modules\secretariat_v2\Services\SaveModel;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class AssignRepository
 * @package app\modules\secretariat_v2\repositories
 * @author Mehran
 */
class AssignRepository
{
    /**
     * Returns name of class.
     * @return string
     */
    public static function className()
    {
        return get_called_class();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     * @author Mehran
     */
    public static function officeEmployees()
    {
        $employees = User::find()
            ->joinWith(['flowOccupation'])
            ->where(['reseller_id' => 1])
            ->andWhere(['user_type_id' => 1])
            ->all();

        foreach ($employees as $employee) {
            /**
             * Find the Occupration for each employee
             */
            $occupation = null;
            if (isset($employee->flowOccupation->occupationData)) {
            $occupation = " - " .  $employee->flowOccupation->occupationData->name . '(' . $employee->flowOccupation->occupationData->description . ')';
            }

            if ($employee->profile != null){
                $employee->profile->name = $employee->profile->name . " " . $employee->profile->family . $occupation ;
            }
        }
        $employees = ArrayHelper::map($employees,'id','profile.name');

        return $employees;
    }

    /**
     * @param $assign
     * @return integer depth
     * @author Mehran
     */
    public static function treeAssignDepth($assign)
    {
        $assigned_employees = OfficeAssign::find()
            ->where(['office_id' => $assign->office_id])
            ->andWhere(['user_id' => Yii::$app->user->identity->id])
            ->orderBy([
                'id' => SORT_DESC
            ])
            ->asArray()
            ->one();

        $depth = $assigned_employees['depth'];
        $depth++;
        return $depth;
    }

    /**
     * @param $office_assign
     * @return array(message, status)
     * @author Mehran
     */
    public static function assignLetter($office_assign)
    {
        $response = [];
        try {
            if (self::verifyAssign($office_assign->office)) {
                $chain = new ChainOfResponsibilities();
                $chain->setSuccessor(new BeginTransaction())->setSuccessor(new CheckAvailability())->setSuccessor(new AssignLetter());
                $chain->handleRequest(new Request($office_assign));

                $response['message'] = 'عملیات با موفقیت انجام شد!';
                $response['status'] = 'success';
            } else {
                $response['message'] = 'شما تنها نامه های ارجاعی به خودتان را میتوانید را ارجاع دهید.';
                $response['status'] = 'error';
            }
        } catch (Exception $exception) {
            $response['message'] = 'خطا در ذخیره سازی اطلاعات';
            $response['status'] = 'error';
        }

        return $response;
    }

    /**
     * @param $office_assign
     * @return array(message, status)
     * @author Mehran
     */
    public static function UpdateAssignLetter($office_assign)
    {
        $response = [];
        try {
            if (self::verifyAssign($office_assign->office)) {
                $chain = new ChainOfResponsibilities();
                $chain->setSuccessor(new BeginTransaction())->setSuccessor(new CheckAvailability())->setSuccessor(new SaveModel());
                $chain->handleRequest(new Request($office_assign));

                $response['message'] = 'عملیات با موفقیت انجام شد!';
                $response['status'] = 'success';
            } else {
                $response['message'] = 'شما تنها نامه های ارجاعی به خودتان را ویرایش کنید.';
                $response['status'] = 'error';
            }
        } catch (Exception $exception) {
            $response['message'] = 'خطا در ذخیره سازی اطلاعات';
            $response['status'] = 'error';
        }

        return $response;
    }

    /**
     * @param $office
     * @return bool
     * @author Mehran
     */
    public static function verifyAssign($office)
    {
        try {
            $office_assign_guard = OfficeAssign::findOne([
                'office_id' => $office->id,
                'user_id' => Yii::$app->user->identity->id,
            ]);

            if (!CommonRepository::checkAvailability($office_assign_guard)) {
                return false;
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $office_assign
     * @return bool
     * @author Mehran
     */
    public static function deleteAssign($office_assign)
    {
        try {
            $chain = new ChainOfResponsibilities();
            $chain->setSuccessor(new BeginTransaction())->setSuccessor(new CheckAvailability())->setSuccessor(new DeleteAssign());
            $chain->handleRequest(new Request($office_assign));

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}
