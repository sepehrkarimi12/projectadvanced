<?php

use app\modules\secretariat_v2\repositories\AssignRepository;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'ویرایش ارجاع به شخص';
$this->params['breadcrumbs'][] = 'دبیرخانه';
$this->params['breadcrumbs'][] = 'نامه ها';
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
                <i class="fa fa-bookmark"></i>  <?= $office_main->title ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="output-container">
                <?= $form->field($office_assign, 'user_id')->widget(\kartik\select2\Select2::classname(), [
                    'data' => AssignRepository::officeEmployees(),
                    'options' => [
                        'placeholder' => 'انتخاب کنید...',
                        'multiple' => false,
                        'id' => 'folders_dropdown'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label("نام گیرنده") ?>
            </div>
            <?= $form->field($office_assign, 'paraph')->textArea(['required'=> true])->label('پاراف') ?>
        </div>
    </div>
    <div class="text-center">
        <?= Html::submitButton('به روزرسانی کن', ['class' => 'btn btn-primary btn-lg save-btn']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>
