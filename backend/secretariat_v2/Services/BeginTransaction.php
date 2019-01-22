<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;
use app\modules\secretariat_v2\models\OfficeMain;
use yii\db\Exception;

/**
 * Check whether a letter is available or not
 * @author Noei
 */
class BeginTransaction extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {
        if ($this->successor != NULL)
        {

            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {

                $this->successor->handleRequest($request);

                $transaction->commit();

            } catch (Exception $e) {
                $transaction->rollback();
                Throw new \mysqli_sql_exception();
            }

        }
    }
}