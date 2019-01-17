<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <div class="col-md-8">
        <h2>Categories :</h2>
        <pre>
	        <?= 
	        	 Html::checkboxList('categories[]',
	        	 $model->getSelectedCategories(),
	        	 $model->getAllCategories() ,['class'=>'']) 
	        ?>
    	</pre>
    </div>

    <?php ActiveForm::end(); ?>

</div>
