<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Network */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="network-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?php
        echo $form->field($model, 'type_id')->widget(Select2::classname(), [
            'data' => $networktypes,
            'language' => 'en',
            'options' => [
                'placeholder' => 'Select network type name ...',
                'id' => 'typeId'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?= $form->field($model, 'ip_address')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<!-- this network need Ip or not -->
<?php 
$script = <<< JS

$('#typeId').change(function(){
    var typeId = $(this).val();
    $.get('index.php?r=network/get', { id : typeId }, function(data){
        var data = $.parseJSON(data);
        alert(data);
    });
});

JS;
$this->registerJS($script);
?>
