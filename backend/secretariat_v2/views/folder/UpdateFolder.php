<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'ویرایش پوشه';
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
                	<?= $form->field($folder_type, 'name')->textInput(['required'=> true])->label(' نام پوشه') ?>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <?= Html::submitButton('ذخیره کن', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>
