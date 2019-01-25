<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'تخصیص بلوک شماره';
$this->params['breadcrumbs'][] = 'LTE';
$this->params['breadcrumbs'][] = 'بلوک';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>
<div class="col-sm-8 col-sm-offset-2">
    <?php $form = ActiveForm::begin()?>
    <div class="portlet grey-silver box">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i><?= $this->title ?>
            </div>
        </div>
        <div class="portlet-body">
            <?= $form->field($number_range, 'from')->textInput(['placeholder'=>'نمونه: 09129612001'])->label('شماره شروع') ?>
            <?= $form->field($number_range, 'to')->textInput(['placeholder'=>'نمونه: 09129612999'])->label('شماره پایان') ?>
            <?= $form->field($number_range, 'reseller_id')->widget(\kartik\select2\Select2::classname(), [
                'data' => \app\modules\reseller\models\Reseller::fetchAsDropDownArray(),
                'options' => [
                    'placeholder' => 'نماینده را انتخاب کنید!',
                    'multiple' => false,
                    'value' => [],
                    'required' => true
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('نمایندگی') ?>
        </div>
    </div>
    <div class="text-center">
        <?= Html::submitButton('ذخیره کن', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>
