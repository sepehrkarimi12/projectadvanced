<?php

namespace app\modules\lte\models\searchModel;

use app\modules\lte\models\LteNumberList;
use yii\data\ActiveDataProvider;

class LteNumberListSearch extends LteNumberList
{
    public function rules()
    {
        return [
            [['number', 'status_id', 'flag_id'], 'safe'],
        ];
    }

    public function search($id, $params)
    {
        $query = LteNumberList::find()->where(['lte_range_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $query->joinWith('status')->joinWith('flag');

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'number', $this->number])
        ->andFilterWhere(['like', 'lte_number_status.id', $this->status_id])
        ->andFilterWhere(['like', 'lte_number_flag.id', $this->flag_id]);

        return $dataProvider;
    }
}
