<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use app\models\Intldate;
use PAMI\Message\Action\MeetmeListAction;
use app\models\MessageHandler;
use yii\debug\models\timeline\DataProvider;

$baseUrl = Url::home(true);

$this->title = 'کاراکترها';
$this->params['breadcrumbs'][] = 'دبیرخانه';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>

<div class="row">
    <div class="col-xs-12">
        <?php
        Pjax::begin(['id' => 'mainGrid1']);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => '',
                    'label' => 'عملیات',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $actionMenu = [
                            [
                                ['category_title' => ''],
                                [
                                    "url" => "/secretariat_v2/setting/office-update-character-setting?id=".$model->id,
                                    "text" => "ویرایش",
                                    "icon" => "fa fa-edit",
                                    "color" => "green-jungle",
                                    "is_ajax" => false,
                                    "id"=>$model->id
                                ],
                            ],
                        ];
                        $actionMenu = json_encode($actionMenu);
                        return "<span class='fa fa-th fa-2x text-success' data-items='" . $actionMenu . "'></span>";
                    },
                ],
                [
                    'label' => 'شماره نامه',
                    'attribute' => 'name',
                    'value' => 'name',
                ],
                [
                    'label' => 'توضیحات',
                    'attribute' => 'description',
                    'value' => 'description',
                ],
                [
                    'label' => 'نمایندگی',
                    'attribute' => 'reseller_id',
                    'value' => function($model) {
                        if ($model->reseller != null) {
                            return $model->reseller->reseller_name;
                        }
                        return null;
                    }
                ],
            ],
        ]);
        Pjax::end();
        ?>
    </div>
</div>