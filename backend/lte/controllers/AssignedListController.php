<?php 

namespace app\modules\lte\controllers;
use Yii;

// use app\modules\lte\models\LteNumberList;
use yii\data\ActiveDataProvider;
use app\modules\lte\models\searchModel\LteNumberListSearch;

class AssignedListController extends \yii\web\Controller
{
	public $layout = '@app/views/layouts/ManageMain';

	public function actionList($id)
	{
		$searchModel = new LteNumberListSearch();
        $dataProvider = $searchModel->search($id, Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	} 

}

?>