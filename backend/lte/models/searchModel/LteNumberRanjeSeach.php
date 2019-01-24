<?php

namespace app\modules\lte\models\searchModel;

use app\modules\lte\models\LteNumberRange;
use yii\data\ActiveDataProvider;

class LteNumberRanjeSeach extends LteNumberRange
{
    public function rules()
    {
        return [
            [['reseller_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'from', 'to'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = LteNumberRange::find();

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
