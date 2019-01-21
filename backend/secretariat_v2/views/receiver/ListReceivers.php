<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\assets\AppAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use kartik\export\ExportMenu;

$baseUrl = Url::home(true);

$this->title = 'لیست اشخاص بیرون از سازمان';
$this->params['breadcrumbs'][] = 'دبیرخانه';
$this->params['breadcrumbs'][] = $this->title;

AppAsset::register($this);

?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>
<div class="row">
    <div class="col-xs-12 col-md-2">
        <a href="<?= $baseUrl ?>secretariat_v2/receiver/add-receiver" class="btn btn-md m-b-1 btn-primary"><i class="fa fa-plus"></i> </span class="h4">افزودن شخص بیرون از سازمان</span></a>

    </div>
    <div class="col-xs-12 col-sm-12">
        <div class="mt-checkbox-inline">
            <form class="show-sub-form float-left">
                <label class="mt-checkbox show-sub <?= (isset($_GET['sub']) && $_GET['sub'] == '1') ? 'active' : '' ?>">
                    <input type="checkbox" name="sub" value="1"  <?= (isset($_GET['sub']) && $_GET['sub'] == '1') ? 'checked="checked"' : '' ?>> <strong class="text-muted">نمایش گیرنده های نمایندگان</strong>
                    <span></span>
                </label>
            </form>
        </div>
    </div>
</div>
<div class="table-responsive">
<?php
//set id for gridview to prevent conflict in js.
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
                            "url" => "/secretariat_v2/receiver/update-receiver?id=" . $model->id,
                            "text" => " ویرایش شخص",
                            "icon" => " fa fa-eye",
                            "is_ajax" => false,
                            "id" => $model->id
                        ],
                    ],
                    [
                        ['category_title' => ''],
                        [
                            "url" => "/secretariat_v2/receiver/declare-person?id=" . $model->id,
                            "text" => "تعیین نوع شخص",
                            "icon" => " fa fa-child",
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
            'label' => 'نام خانوادگی',
            'attribute' => 'family',
            'value' => 'family',
        ],
        [
            'label' => 'تلفن',
            'attribute' => 'tel',
            'value' => 'tel',
        ],
        [
            'label' => 'شرکت/سازمان',
            'attribute' => 'company_name',
            'value' => 'company_name',
        ],
        [
            'label' => 'واحد/معاونت',
            'attribute' => 'unit_name',
            'value' => 'unit_name',
        ],
        [
            'label' => 'سمت',
            'attribute' => 'occuaption_name',
            'value' => 'occuaption_name',
        ],

        [
            'label' => 'نوع شخص',
            'filter' => \app\modules\secretariat_v2\models\OfficePeopleType::fetchAsDropDownArray(),
            'attribute' => 'office_people_type_id',
            'value' => function ($model) {
                if (isset($model->officePeopleType)) {
                    return $model->officePeopleType->name;
                }
                return '-';
            },
        ],
        [
            'label' => 'نماینده',
            'attribute' => 'reseller_id',
            'value' => 'reseller.reseller_name',
        ],
    ],
]);
?>
</div>
<?php
Pjax::end();
?>
<?php
$js = <<<JS
$( document ).ready(function() {
   $('#w0-excel2007 ').css({'color': 'white'});
   
   $('#w0-excel2007 .fa-file-excel-o').css('color', 'white');
   $( '#w0-excel2007  ').parent().css({'list-style-type': 'none'});
});

JS;
$this->registerJS($js);
?>
