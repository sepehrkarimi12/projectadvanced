<?php
namespace app\modules\secretariat_v2\models\searchModels;

use app\modules\secretariat_v2\models\OfficeMain;
use yii\data\ActiveDataProvider;
use Yii;
use app\models\Intldate;

class OfficeLettersSearch extends OfficeMain
{
    public $access_level;
    public $category;
    public $priority;
    public $is_archived;
    public $status_id;
    public $type;

    // set this to 1 if you wanna sea all letters
    public $all;

    public function rules()
    {
        return
            [
                [['access_level', 'status_id', 'category', 'priority', 'is_archived', 'archive_number', 'type', 'title', 'receiver_id', 'access_level','priority','created_at'], 'safe']
            ];
    }

    public function search($params)
    {
        $query=OfficeMain::find();
        $query->where(['isDeleted' => 0]);
        if ($this->all
            // TODO
            //&& Yii::$app->user->can('SecretariatV2ListallLettersPerm')
            ) {
            $query->Where(['created_by' => Yii::$app->user->identity->id]);
            $query->where(['reseller_id' => Yii::$app->user->identity->reseller_id]);
        } else {
            // in this section we find letters which are for users reseller and the user is creator or the letter is assigned to the user someway
            $query->joinWith(['officeAssigns']);
            $query->where(['office_main.reseller_id' => Yii::$app->user->identity->reseller_id]);
            $query->andWhere(['or',
                ['office_assign.user_id' => Yii::$app->user->identity->id],
                ['office_main.created_by' => Yii::$app->user->identity->id]
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $intldate = new Intldate;
        //search date
        if ($this->created_at != null) {
            $expIssuance = explode(',', $this->created_at);
            $Issuance = explode('/', $expIssuance[0]);

            if (isset($Issuance[0]) && isset($Issuance[1]) && isset($Issuance[2])) {
                if (array_key_exists(0, $Issuance)) {
                    $yearIssuance = $Issuance[0];
                }
                if (array_key_exists(1, $Issuance)) {
                    $monthIssuance = $Issuance[1];
                }
                if (array_key_exists(2, $Issuance)) {
                    $dayIssuance = $Issuance[2];
                }
                if (isset($monthIssuance)) {
                    if ($monthIssuance == '01' || $monthIssuance == '02' || $monthIssuance == '03' || $monthIssuance == '04' || $monthIssuance == '05' || $monthIssuance == '06' || $monthIssuance == '07' || $monthIssuance == '08' || $monthIssuance == '09') {
                        $monthIssuance = substr($monthIssuance, 1);
                    }
                }
                if (isset($dayIssuance)) {
                    if ($dayIssuance == '01' || $dayIssuance == '02' || $dayIssuance == '03' || $dayIssuance == '04' || $dayIssuance == '05' || $dayIssuance == '06' || $dayIssuance == '07' || $dayIssuance == '08' || $dayIssuance == '09') {
                        $dayIssuance = substr($dayIssuance, 1);
                    }
                }

                //if date has not time
                if (!isset($expIssuance[1])) {
                    $startDate = $intldate->createPersianTimestamp($yearIssuance, $monthIssuance, $dayIssuance, 00, 00, 00);
                    $endDate = $intldate->createPersianTimestamp($yearIssuance, $monthIssuance, $dayIssuance, 23, 59, 59);
                } else {
                    //if date has time
                    $expTime = explode(':', $expIssuance[1]);
                    $payDate = $intldate->createPersianTimestamp($yearIssuance, $monthIssuance, $dayIssuance, $expTime[0], $expTime[1], $expTime[2]);
                }
            }
        }

        //filter for search
        if ($this->created_at != null && (isset($startDate) || isset($endDate) || isset($payDate))) {
            if ($this->created_at != null && (isset($startDate) && isset($endDate)))
                $query->andWhere(['between', 'secretariat.created_at', $startDate, $endDate]);
            elseif ($this->created_at != null && (isset($payDate)))
                $query->andWhere(['like', 'secretariat.created_at', $payDate]);
        }

        $query->andWhere(['like', 'archive_number', $this->archive_number]);
        $query->andWhere(['like', 'office_type_id', $this->type]);
        $query->andWhere(['status_id' => $this->status_id]);
        $query->andWhere(['like', 'title', $this->title]);
        $query->andWhere(['like', 'access_level_id', $this->access_level]);
        $query->andWhere(['like', 'priority_id', $this->priority]);
        $query->andWhere(['like', 'category_id', $this->category]);
        $query->andWhere(['like', 'is_archived', $this->is_archived]);
        return $dataProvider;

    }
}
