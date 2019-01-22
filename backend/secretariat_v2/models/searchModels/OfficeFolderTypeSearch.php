<?php

namespace app\modules\secretariat_v2\models\searchModels;

use app\modules\reseller\models\Reseller;
use app\modules\reseller\models\ResellerNode;
use app\modules\secretariat_v2\models\OfficeFolderType;
use yii\data\ActiveDataProvider;
use Yii;

class OfficeFolderTypeSearch extends OfficeFolderType
{
    public $sub;
    public $reseller_id;
    public $name;

    public function rules()
    {
        return
            [
                [['reseller_id', 'sub_folder','name', 'reseller_name'], 'safe']
            ];
    }

    public function search($params)
    {
        if ($this->sub == 1) {
            $user = Yii::$app->user->identity;
            $reseller = Reseller::findOne($user->reseller_id);
            $reseller_node = ResellerNode::findOne($reseller->node_id);

            $query = OfficeFolderType::find();
            $query->joinWith(['reseller', 'reseller.node']);
            $left = $reseller_node->lft;
            $right = $reseller_node->rgt;

            $query->andWhere(['>=', 'lft', $left])
                ->andWhere(['<=', 'rgt', $right]);
        } else {
            $query = OfficeFolderType::find();
            $query->joinWith(['reseller']);
            $query->Where(['reseller_id'=> Yii::$app->user->identity->reseller_id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'reseller.reseller_name', $this->reseller_id]);

        return $dataProvider;
    }
}
