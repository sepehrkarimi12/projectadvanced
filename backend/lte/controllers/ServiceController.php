<?php

namespace app\modules\lte\controllers;

use app\models\AAA;
use app\models\Bulldog;
use app\models\Constant;
use app\models\Intldate;
use app\models\ManageService;
use app\models\MessageHandler;
use app\modules\lte\models\LteNumberRange;
use app\modules\lte\repositories\ServiceRepository;
use app\modules\manage\models\Adsl;
use app\modules\manage\models\Lan;
use app\modules\manage\models\Lte;
use app\modules\manage\models\Service;
use app\modules\manage\models\Shahkar;
use app\modules\manage\models\User;
use app\modules\manage\models\Wireless;
use app\modules\package\models\ServiceGroup;
use app\modules\productmaker\models\ProductMakerFactorLog;
use app\modules\telegram\models\Telegram;
use app\repositories\CommonRepository;
use Exception;
use mysqli_sql_exception;
use nox\helpers\ArrayHelper;
use yii\filters\AccessControl;
use Yii;
use yii\web\NotFoundHttpException;

class ServiceController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/ManageMain';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['test'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['add-lte', 'check-phone-availability-ajax'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function ($rule, $action) {
//                            return Yii::$app->user->can('LteServiceAddLtePerm');
//                        }
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionTest()
    {
        pd("test");
    }

    /**
     * This action has been used for create Lte service
     * This action needs to refactor because its too long, now it work good(Bezan Biad Bala)
     * @param $user_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @author Mehran
     */
    public function actionAddLte($user_id)
    {
        $service = new Service;
        $service->scenario = Service::SCENARIO_LTE;
        $lte = new Lte;

        $user_result = User::findOne($user_id);
        if (!CommonRepository::checkAvailability($user_result)) {
            Throw new NotFoundHttpException('user model not found');
        }

        Bulldog::checkAccess([
            'reseller_id' => $user_result->reseller->id,
            'city_ids' => $user_result->cities,
            'on' => ['reseller', 'city'],
        ]);

        /**
         * get factors
         */
        $product_maker_factor_log = new ProductMakerFactorLog;
        $factor_product_maker = ServiceRepository::getProductmakerFactor(Constant::LTE);

        if ($service->load(Yii::$app->request->post()) && $lte->load(Yii::$app->request->post())) {
            $service_log = null;
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $lte->mobile_number = preg_replace('/0/', '98', $lte->input_mobile_number, 1);
                if (Lte::findOne(['mobile_number' => $lte->mobile_number]) != null) {
                    MessageHandler::ShowMessage('این شماره قبلأ به ثبت رسیده', 'error');
                    return $this->redirect(Yii::$app->request->referrer);
                }
                /**
                 * Find the reseller service group
                 */
                $service_group = ServiceGroup::findOne(['reseller_id' => Yii::$app->user->identity->reseller_id]);

                $service = ManageService::service()->createService(Constant::LTE, $service->address, $lte->input_mobile_number, $user_id, $service_group->id, 1024, "مالک", 348, $service->platform);
                $service_log = $service;
                if ($service == null) {
                    Throw new mysqli_sql_exception('Can not save in service table');
                }
                $lte->service_id = $service->id;
                /**
                 * create ibsng user and get the ibs unique ID.
                 */
                $ibs_id = AAA::connection()->addNewUser();
                $lte->ibs_id = $ibs_id;

                /**
                 * generating ibsng username and password for created user.
                 */
                $ibs_username = $lte->mobile_number;
                $ibs_password = 'baharnet';

                /**
                 * set username and password for user's ibsng account.
                 */
                if (AAA::connection()->setUsernameAndPassword($ibs_id, $ibs_username, $ibs_password)) {
                    $lte->ibs_username = $ibs_username;
                    $lte->ibs_password = $ibs_password;
                }

                /**
                 * Set 50MB credit for new users.
                 */
                if (Yii::$app->user->identity->reseller_id == 3) {
                    AAA::connection()->addUserCredit($ibs_username, 500);
                    AAA::connection()->expireDateUser($ibs_username, 3);

                } else {
                    AAA::connection()->addUserCredit($ibs_username, 50);
                }

                /**
                 * parameters here are because of ibsng configuration and can be changed
                 */
                $lte->ibs_group = 'Tehran-128K-0G';
                $lte->isp = 'Main';
                $lte->save();

                /**
                 * Set Ibsng custome fields
                 */
                $fields = array(
                    'custom_field_Name' => $user_result->profile->name,
                    'custom_field_Family' => $user_result->profile->family,
                    'custom_field_DateOfBirth' => $user_result->profile->shahkar_birthDate,
                    'custom_field_Mobile' => $user_result->profile->mobile,
                    'custom_field_IdCard' => $user_result->profile->id_card,
                    'custom_field_Phone' => $lte->mobile_number,
                    'custom_field_Address' => $service->address,
                    'custom_field_Email' => $user_result->email,
                    'custom_field_Reseller' => (string)$user_result->reseller_id,
                    'custom_field_City' => (string)$service->city_id,
                );

                AAA::connection()->setUserCustomFields($ibs_username, $fields);

                /**
                 * set in RAS IPPOOL
                 */
                if (Yii::$app->user->identity->reseller_id == 3) {
                    AAA::connection()->setInRasIpPool($ibs_username, 'MTS');
                }
                if (Yii::$app->user->identity->reseller_id == 9) {
                    AAA::connection()->setInRasIpPool($ibs_username, 'GHZ');
                }
                if (Yii::$app->user->identity->reseller_id == 31) {
                    AAA::connection()->setInRasIpPool($ibs_username, 'MZN');
                }
                if (Yii::$app->user->identity->reseller_id == 37) {
                    AAA::connection()->setInRasIpPool($ibs_username, 'KSN');
                }
                if (Yii::$app->user->identity->reseller_id == 7) {
                    AAA::connection()->setInRasIpPool($ibs_username, 'MSM');
                }
                if (Yii::$app->user->identity->reseller_id == 8) {
                    AAA::connection()->setInRasIpPool($ibs_username, 'OLK');
                }
                if (Yii::$app->user->identity->reseller_id == 25) {
                    AAA::connection()->setInRasIpPool($ibs_username, 'FJD');
                }
                if (Yii::$app->user->identity->reseller_id == 4) {
                    AAA::connection()->setInRasIpPool($ibs_username, 'ARN');
                }
                if (Yii::$app->user->identity->reseller_id == 14) {
                    AAA::connection()->setInRasIpPool($ibs_username, 'GLN');
                }

                /**
                 * Sending log for zirsakht csv file
                 */
                if ($service->is_expire == 0) {
                    \app\modules\manage\models\Dumper::dumpAndWriteSingle($user_result, $service);
                }

                /**
                 * create log in productmaker_log when selected factor
                 */
                if (isset(Yii::$app->request->post()['ProductMakerFactorLog']['selected_factors'])) {
                    $productmaker_factors = Yii::$app->request->post()['ProductMakerFactorLog']['selected_factors'];
                    if ($productmaker_factors != null) {
                        $user_virtual_credit = Yii::$app->user->identity->virtual_credit;
                        foreach ($productmaker_factors as $key_product => $val_product) {
                            $factor = $factor_product_maker[$key_product];
                            if ($factor['is_optional'] == 1) {
                                if ($factor['original_price'] < $user_virtual_credit) {
                                    $user_virtual_credit -= $factor['original_price'];
                                    $factor_id = $factor['id'];
                                    $factor_status = Constant::PAID;
                                } else {
                                    return Yii::$app->print->ShowMessage('لطفا حساب کاربری خود را شارژ کنید.', 'error', [$this, "/manage/customer/user-actions?id={$id}"]);
                                }
                            }
                            if ($val_product != null) {
                                $factor_id = $key_product;
                                $factor_status = Constant::UNPAID;
                            }
                            $productMakerFactorLogDB = new ProductMakerFactorLog;
                            $productMakerFactorLogDB->service_id = $service->id;
                            $productMakerFactorLogDB->factor_id = $factor_id;
                            $productMakerFactorLogDB->virtual_credit_id = null;
                            $productMakerFactorLogDB->committer_id = Yii::$app->user->identity->id;
                            $productMakerFactorLogDB->created_at = time();
                            $productMakerFactorLogDB->status = $factor_status;
                            $productMakerFactorLogDB->save();
                        }
                        // Update current user virtual credit
                        $current_user = User::findOne(Yii::$app->user->identity->id);
                        $current_user->virtual_credit = $user_virtual_credit;
                        $current_user->save();
                    }
                }

                $transaction->commit();
                MessageHandler::ShowMessage('سرویس با موفقیت افزوده شد.', 'success');
                return $this->redirect('/manage/customer/user-actions?id=' . $user_id);
            } catch (Exception $e) {
                $transaction->rollBack();
                if ($service_log != null) {
                    $service = Service::findOne($service_log->id);
                    $service->delete();
                }
                MessageHandler::ShowMessage('مشکلی در ثبت سرویس وجود دارد', 'error');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('AddLte', [
            'user_result' => $user_result,
            'service' => $service,
            'lte' => $lte,
            'factor_product_maker' => $factor_product_maker,
            'product_maker_factor_log' => $product_maker_factor_log,
        ]);
    }

    /**
     * This action for check phone availability for reseller abd check number is sold or not
     * @return array|null
     * @author Mehran
     */
    public function actionCheckPhoneAvailabilityAjax()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            if (Yii::$app->request->isAjax && $data = Yii::$app->request->post()) {
                $mobile = $data['mobile'];
                if (strlen($mobile) != 11) {
                    return [
                        'statusCode' => 5,
                        'statusMessage' => 'شماره را کامل وارد نمایید!',
                    ];
                }
                return LteNumberRange::checkPhoneAvailability($mobile);
            }
        } catch (Exception $exception) {
            return [
                'statusCode' => 4,
                'statusMessage' => 'امکان استعلام شماره وجود ندارد!',
            ];
        }
    }
}