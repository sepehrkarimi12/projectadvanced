<?php
namespace app\modules\secretariat_v2\models\searchModels;

use app\modules\secretariat_v2\models\OfficeCharacter;
use app\modules\secretariat_v2\models\OfficePrefixFormat;
use yii\data\ActiveDataProvider;

class OfficeFormatSearch extends OfficePrefixFormat
{

  public function rules()
  {
    return
        [
          [['name', 'description', 'reseller_id', 'format'],'safe']
        ];
  }

  public function search($params)
  {
    $query = OfficePrefixFormat::find();

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
    ]);

    if (!($this->load($params) && $this->validate())) {
        return $dataProvider;
    }

    $query->andFilterWhere(['like' ,'name' , $this->name])
          ->andFilterWhere(['like' ,'description' , $this->description])
          ->andFilterWhere(['like' ,'format' , $this->format]);

    return $dataProvider;
  }
}
