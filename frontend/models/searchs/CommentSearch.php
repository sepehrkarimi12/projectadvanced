<?php

namespace frontend\models\searchs;

use yii\base\Model;
use common\components\Zmodel;
use yii\data\ActiveDataProvider;
use frontend\models\Comment;

/**
 * CommentSearch represents the model behind the search form of `frontend\models\Comment`.
 */
class CommentSearch extends Comment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_deleted', 'created_at', 'deletor_id', 'deleted_at'], 'integer'],
            [['text', 'customer_id', 'creator_id'], 'safe'],
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
        $query = Comment::find()->where(['!=','comment.is_deleted',Zmodel::$active]);

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

        $query->joinWith('customer')->andOnCondition(['!=','customer.is_deleted',Zmodel::$active]);
        $query->joinWith('creator');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->created_at,
            'deletor_id' => $this->deletor_id,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'customer.lname', $this->customer_id])
            ->andFilterWhere(['like', 'user.username', $this->creator_id]);

        return $dataProvider;
    }
}
