<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use common\components\Zmodel;

/* @var $this yii\web\View */
/* @var $model frontend\models\Service */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php
        echo $form->field($model, 'customer_id')->widget(Select2::classname(), [
            'data' => Zmodel::getAllCustomers(),
            'language' => 'en',
            'options' => [
                'placeholder' => 'Select network name ...',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?php
        echo $form->field($model, 'type_id')->widget(Select2::classname(), [
            'data' => $service_types,
            'language' => 'en',
            'options' => [
                'placeholder' => 'Select Service Type name ...',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?php
        echo $form->field($model, 'network_id')->widget(Select2::classname(), [
            'data' => $networks,
            'language' => 'en',
            'options' => [
                'placeholder' => 'Select Network name ...',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ppoe_username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ppoe_password')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
