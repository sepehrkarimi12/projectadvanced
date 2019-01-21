<?php
namespace app\modules\secretariat_v2\models\searchModels;

use app\modules\secretariat_v2\models\OfficeCharacter;
use app\modules\secretariat_v2\models\OfficeType;
use yii\data\ActiveDataProvider;

class OfficeLetterCharacterSearch extends OfficeType
{

  public function rules()
  {
    return
        [
          [['name', 'created_at', 'updated_at', 'character_id', 'format_id'],'safe']
        ];
  }

  public function search($params)
  {
    $query = OfficeType::find();

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
    ]);

    if (!($this->load($params) && $this->validate())) {
        return $dataProvider;
    }

    $query->andFilterWhere(['like' ,'name' , $this->name]);

    return $dataProvider;
  }
}
