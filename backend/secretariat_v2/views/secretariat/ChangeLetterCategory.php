<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'تنظیمات شماره نامه';
$this->params['breadcrumbs'][] = 'دبیرخانه';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>

<div class="col-sm-8 col-sm-offset-2">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>
    <div class="portlet grey-silver box">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i> <?= $this->title ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-12 check-category">
                        <?= $form->field($office_main, 'category_id')->widget(\kartik\select2\Select2::classname(), [
                            'data' => \app\modules\secretariat_v2\models\OfficeCategory::fetchAsDropDownArray(),
                            'options' => [
                                'placeholder' => 'دسته بندی را انتخاب کنید',
                                'multiple' => false,
                                'id' => 'officemain-category'
                            ],])->label('دسته بندی نامه') ?>
                    </div>
                    <div class="col-sm-12">
                        <div class="category-container none">
                            <?= $form->field($office_main, 'secretariat_numbers')->widget(\kartik\select2\Select2::classname(), [
                                'data' => \app\modules\secretariat_v2\models\OfficeMain::getOfficeNumbers(),
                                'options' => [
                                    'placeholder' => 'انتخاب کنید...',
                                    'multiple' => false,
                                    'id' => 'numbers_dropdown'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label("شماره نامه را وارد کنید") ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="text-center">
    <?= Html::submitButton('ذخیره کن', ['class' => 'btn btn-primary btn-lg save-btn']) ?>
</div>
<?php ActiveForm::end() ?>

<?php
$js=<<<JS
$('#officemain-category').change(function() {
    var selected_type = $(this).val();
    if (selected_type == 1) {
        $('.category-container').hide();
    } else {
        $('.category-container').show(); 
    }
});
JS;
$this->registerJS($js);
?>
