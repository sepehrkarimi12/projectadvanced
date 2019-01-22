<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'ویرایش شخص بیرون از سازمان';
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
                <i class="fa fa-bookmark"></i><?= $this->title; ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
            	<div class="col-xs-12">
                	<?= $form->field($receiver, 'name')->textInput(['required'=> true])->label('نام') ?>
                	<?= $form->field($receiver, 'family')->textInput(['required'=> true])->label('نام خانوادگی') ?>
                    <?= $form->field($receiver, 'tel')->input('number', ['required'=> true])->label('تلفن') ?>
                	<?= $form->field($receiver, 'company_name')->textInput(['required'=> true])->label('نام شرکت/سازمان') ?>
                	<?= $form->field($receiver, 'unit_name')->textInput(['required'=> true])->label('نام واحد/معاونت') ?>
                	<?= $form->field($receiver, 'occuaption_name')->textInput(['required'=> true])->label('سمت') ?>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <?= Html::submitButton('ذخیره کن', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>

<?php
$js=<<<JS
$(function() {
	$('input[required], textarea, select')
	$('input.hasDatepicker').attr('required', true);
	$('input.hasDatepicker').on('change', function(){
		if($(this).val() != '') {
			$(this).attr('required', false);
		} else {
			$(this).attr('required', true);
		}
	});
	$('input[required], textarea, select').not('.hasDatepicker').on('invalid', function(){
		return this.setCustomValidity('این فیلد الزامی است.');
	}).on('input', function(){
		return this.setCustomValidity('');
	});
});
JS;
$this->registerJS($js);

?>