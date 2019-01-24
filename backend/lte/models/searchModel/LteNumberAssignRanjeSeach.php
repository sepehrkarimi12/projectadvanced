<?php

namespace app\modules\lte\models\searchModel;

use app\modules\lte\models\LteNumberRange;
use yii\data\ActiveDataProvider;

class LteNumberAssignRanjeSeach extends LteNumberRange
{
    public $parent_reseller;
    public function rules()
    {
        return [
            [['reseller_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'from', 'to'], 'safe'],
            [['parent_reseller'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = LteNumberRange::find()
            ->joinWith(['lteNumberLists as lte_p'])
            ->where(['reseller_id' => $this->parent_reseller])
            ->where(['not', ['lte_p.reseller_id' => null]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'from', $this->from])
            ->andFilterWhere(['like', 'to', $this->to]);

        return $dataProvider;
    }
}
