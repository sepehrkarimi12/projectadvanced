<?php

namespace frontend\models\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Network;

/**
 * NetworkSearch represents the model behind the search form of `frontend\models\Network`.
 */
class NetworkSearch extends Network
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'is_deleted', 'creator_id', 'created_at', 'deletor_id', 'deleted_at'], 'integer'],
            [['name', 'address', 'ip_address'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Network::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type_id' => $this->type_id,
            'is_deleted' => $this->is_deleted,
            'creator_id' => $this->creator_id,
            'created_at' => $this->created_at,
            'deletor_id' => $this->deletor_id,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address]);

        return $dataProvider;
    }
}
