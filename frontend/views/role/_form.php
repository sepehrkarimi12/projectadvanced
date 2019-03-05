<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="role-form">

    <div class="col-md-4">
        
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>


    </div>

        <div class="col-md-8">
            <h2>Permissions :</h2>
            <pre>
                <?= Html::checkboxList('permissions[]', $model->getSelectedPermissions(), $model->getAllPermissions() ,['class'=>'']) ?>
            </pre>
        </div>

        <?php ActiveForm::end(); ?>

</div>
