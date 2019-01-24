<?php

use yii\grid\GridView;
use yii\helpers\Url;
use app\assets\AppAsset;
use yii\widgets\Pjax;
use app\modules\lte\models\LteNumberStatus;
use app\modules\lte\models\LteNumberFlag;

$baseUrl = Url::home(true);

$this->title = ' لیست تخصیص داده شده ها ';
$this->params['breadcrumbs'][] = 'Lte';
$this->params['breadcrumbs'][] = $this->title;

AppAsset::register($this);

?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <a href="<?= $baseUrl ?>lte/block/list" class="btn btn-md m-b-1 btn-default"><i class="fa fa-level-up"></i> </span class="h4">بازگشت</span></a>
    </div>
</div>

<?php Pjax::begin(); ?>

<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
        	'attribute' => 'number',
        	'label' => 'شماره'
        ],
        [
            'label' => 'در بازه',
            'attribute' => 'lte_range_id',
            'value' => function ($model) {
                return $model->lteRange->from . ' تا ' . $model->lteRange->to;
            } 
        ],
        [
            'label' => 'وضعیت',
            'attribute' => 'status_id',
            'value' => function ($model) {
                return $model->status->name;
            },
            'filter' => LteNumberStatus::getListOfStatusForDropDown(),
            'filterInputOptions' => [
               'class' => 'form-control',         
               'prompt' => 'همه'
            ],
        ],
        [
            'label' => 'flag',
            'attribute' => 'flag_id',
            'value' => function ($model) {
                return $model->flag->name;
            },
            'filter' => LteNumberFlag::getListOfFlagForDropDown(),
            'filterInputOptions' => [
               'class' => 'form-control',         
               'prompt' => 'همه'
            ],
        ],
    ],
]);

?>

<?php Pjax::end(); ?>