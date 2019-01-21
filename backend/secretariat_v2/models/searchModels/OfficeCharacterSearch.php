<?php
namespace app\modules\secretariat_v2\models\searchModels;

use app\modules\secretariat_v2\models\OfficeCharacter;
use yii\data\ActiveDataProvider;

class OfficeCharacterSearch extends OfficeCharacter
{

  public function rules()
  {
    return
        [
          [['name', 'description', 'reseller_id'],'safe']
        ];
  }

  public function search($params)
  {
    $query = OfficeCharacter::find();

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
    ]);

    if (!($this->load($params) && $this->validate())) {
        return $dataProvider;
    }

    $query->andFilterWhere(['like' ,'name' , $this->name])
          ->andFilterWhere(['like' ,'description' , $this->description]);

    return $dataProvider;
  }
}
