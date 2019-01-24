<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\assets\AppAsset;
use app\models\Intldate;
use app\models\Constant;

$baseUrl = Url::home(true);

$this->title = ' لیست بلوک ها ';
$this->params['breadcrumbs'][] = 'Lte';
$this->params['breadcrumbs'][] = $this->title;
AppAsset::register($this);
?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <a href="<?= $baseUrl ?>lte/block/assign?id=<?= $range_id ?>" class="btn btn-md m-b-1 btn-primary"><i class="fa fa-plus"></i> </span class="h4"> تخصیص شماره </span></a>
    </div>
</div>
<div class="table-responsive">
    <?php
    Pjax::begin(['id' => 'mainGrid']);
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
                                "url" => "/lte/block/update-assign?assign_id=" . $model->id,
                                "text" => "ویرایش",
                                "icon" => "fa fa-edit",
                                "is_ajax" => false,
                                "id" => $model->id,
                                "color" => "blue"
                            ],
                        ],
                        [
                            ['category_title' => ''],
                            [
                                "url" => "/lte/block/view-assign-block?assign_id=" . $model->id,
                                "text" => "مشاهده بلوک شماره",
                                "icon" => "fa fa-eye",
                                "is_ajax" => false,
                                "id" => $model->id,
                                "color" => "green"
                            ],
                        ],
                    ];
                    $actionMenu = json_encode($actionMenu);
                    return "<span class='fa fa-th fa-2x text-success' data-items='".$actionMenu."'></span>";
                },
            ],
            [
                'attribute' => 'id',
                'value' => 'id',
                'label' => ' از شماره'
            ],
        ],
    ]);
    ?>
</div>