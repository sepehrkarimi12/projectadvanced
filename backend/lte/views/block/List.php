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
        <a href="<?= $baseUrl ?>lte/block/create" class="btn btn-md m-b-1 btn-primary"><i class="fa fa-plus"></i> </span class="h4"> ایجاد بلوک </span></a>
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
                                "url" => "/lte/block/assign?id=" . $model->id,
                                "text" => "تخصیص",
                                "icon" => "fa fa-arrows-h",
                                "is_ajax" => false,
                                "id" => $model->id
                            ],
                        ],
                        [
                            ['category_title' => ''],
                            [
                                "url" => "/lte/assigned-list/list?id=" . $model->id,
                                "text" => "شماره های اختصاص داده شده",
                                "icon" => "fa fa-bars",
                                "is_ajax" => false,
                                "id" => $model->id,
                                "color" => "blue"
                            ],
                        ],
                    ];
                    $actionMenu = json_encode($actionMenu);
                    return "<span class='fa fa-th fa-2x text-success' data-items='".$actionMenu."'></span>";
                },
            ],
            [
                'attribute' => 'from',
                'value' => 'from',
                'label' => ' از شماره'
            ],
            [
                'attribute' => 'to',
                'value' => 'to',
                'label' => ' تا شماره'
            ],
            [
                'attribute' => 'to',
                'value' => function($model) {
                    if ($model->reseller != null) {
                        return $model->reseller->reseller_name;
                    }
                },
                'label' => ' نماینده'
            ],
            [
                'attribute' => 'to',
                'value' => function($model) {
                    if ($model->created_at != null) {
                        return Intldate::get()->timestampToPersian($model->created_at);
                    }
                },
                'label' => ' تاریخ ایجاد'
            ],
            [
                'attribute' => 'to',
                'value' => function($model) {
                    if ($model->updated_at != null) {
                        return Intldate::get()->timestampToPersian($model->updated_at);
                    }
                },
                'label' => ' تاریخ آخرین ویرایش'
            ],
        ],
    ]);
    ?>
</div>