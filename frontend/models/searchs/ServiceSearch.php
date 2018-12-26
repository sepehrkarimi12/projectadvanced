<?php

namespace frontend\models\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Service;
use common\components\Zmodel;

/**
 * ServiceSearch represents the model behind the search form of `frontend\models\Service`.
 */
class ServiceSearch extends Service
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_deleted', 'creator_id', 'created_at', 'deletor_id', 'deleted_at'], 'integer'],
            [['name', 'address', 'ppoe_username', 'ppoe_password', 'customer_id', 'type_id', 'network_id',], 'safe'],
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
        $query = Service::find()->where(['!=','service.is_deleted',Zmodel::$active]);

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

        $query->joinWith('customer')->onCondition(['!=','customer.is_deleted',1])->all();
        $query->joinWith('type')->onCondition(['!=','servicetype.is_deleted',1])->all();
        $query->joinWith('network')->onCondition(['!=','network.is_deleted',1])->all();

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_deleted' => $this->is_deleted,
            'creator_id' => $this->creator_id,
            'created_at' => $this->created_at,
            'deletor_id' => $this->deletor_id,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'service. address', $this->address])
            ->andFilterWhere(['like', 'ppoe_username', $this->ppoe_username])
            ->andFilterWhere(['like', 'ppoe_password', $this->ppoe_password])
            ->andFilterWhere([
                'OR',
                ['like', 'customer.fname', $this->customer_id],
                ['like', 'customer.lname', $this->customer_id]        
            ])
            ->andFilterWhere(['like', 'servicetype.title', $this->type_id])
            ->andFilterWhere(['like', 'network.name', $this->network_id]);

        return $dataProvider;
    }
}
