<?php
namespace app\modules\secretariat_v2\models\searchModels;

use app\modules\secretariat_v2\models\OfficePeople;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\reseller\models\Reseller;
use app\modules\reseller\models\ResellerNode;

class OfficePeopleSearch extends OfficePeople
{
    /**
     * Setting sub property to know showing sub users is allowed or not.
     */
    public $sub_users;

  public function rules()
  {
    return
        [
          [['name', 'family', 'tel', 'company_name', 'occuaption_name', 'unit_name', 'reseller_id', 'office_people_type_id'],'safe']
        ];
  }

  public function search($params)
  {
    if ($this->sub_users == 1) {
        $user = Yii::$app->user->identity;
        $reseller = Reseller::findOne($user->reseller_id);
        $reseller_node = ResellerNode::findOne($reseller->node_id);

        $query = OfficePeople::find();
        $query->joinWith(['reseller', 'reseller.node', 'officePeopleType as of_type']);

        $left = $reseller_node->lft;
        $right = $reseller_node->rgt;

        $query->andFilterWhere(['>=', 'lft', $left])
            ->andFilterWhere(['<=', 'rgt', $right]);
    } else {
        $query = OfficePeople::find();
        $query->where(['reseller_id' => Yii::$app->user->identity->reseller_id]);
    }

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
    ]);

    if (!($this->load($params) && $this->validate())) {
        return $dataProvider;
    }

    $query->andFilterWhere(['like' ,'name' , $this->name])
          ->andFilterWhere(['like' ,'family' , $this->family])
          ->andFilterWhere(['like' ,'tel' , $this->tel])
          ->andFilterWhere(['like' ,'company_name' , $this->company_name])
          ->andFilterWhere(['like' ,'occuaption_name' , $this->occuaption_name])
          ->andFilterWhere(['like' ,'unit_name' , $this->unit_name])
          ->andFilterWhere(['like' ,'reseller.reseller_name' , $this->reseller_id])
          ->andFilterWhere(['like' ,'office_people_type_id' , $this->office_people_type_id]);

    return $dataProvider;
  }
}
