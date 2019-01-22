<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'لیست پوشه ها';
$this->params['breadcrumbs'][] = 'دبیرخانه';
$this->params['breadcrumbs'][] = $this->title;

$baseUrl = Url::home(true);
?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>
<div class="row">
    <div class="col-xs-12 col-md-2">
        <a href="<?= $baseUrl ?>secretariat_v2/folder/add-folder" class="btn btn-md m-b-1 btn-primary"><i class="fa fa-plus"></i> </span class="h4">افزودن پوشه</span></a>
    </div>
    <div class="col-xs-12" >
        <div class="mt-checkbox-inline">
            <form class="show-sub-form float-right">
                <label class="mt-checkbox show-sub <?= (isset($_GET['sub']) && $_GET['sub'] == '1') ? 'active' : '' ?>">
                    <input type="checkbox" name="sub" value="1"  <?= (isset($_GET['sub']) && $_GET['sub'] == '1') ? 'checked="checked"' : '' ?>> <strong class="text-muted">نمایش کل پوشه ها</strong>
                    <span></span>
                </label>
            </form>
        </div>
    </div>
    <div class="col-xs-12">
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
                                "url" => "/secretariat_v2/folder/update-folder?id=" . $model->id,
                                "text" => " ویرایش پوشه",
                                "icon" => " fa fa-eye",
                                "is_ajax" => false,
                                "id" => $model->id
                            ],
                        ],

                    ];
                    $actionMenu = json_encode($actionMenu);
                    return "<span class='fa fa-th fa-2x text-success' data-items='".$actionMenu."'></span>";
                },
            ],
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions'=>['class'=>'bold'],
            ],
            [
                'label' => 'نام',
                'attribute' => 'name',
                'value' => 'name',
            ],
            [
                'label' => 'نماینده',
                'attribute' => 'reseller_id',
                'value'=>function ($dataProvider) {
                    return $dataProvider->reseller->reseller_name;
                },
                ],
        ],
    ]);
    ?>
</div>
    </div>
</div>
