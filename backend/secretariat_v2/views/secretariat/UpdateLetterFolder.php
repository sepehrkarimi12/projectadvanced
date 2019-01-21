<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'ویرایش  پوشه';
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
                <i class="fa fa-bookmark"></i>مدیریت پوشه ها
            </div>
        </div>
        <div class="portlet-body">
            <i class="fa fa-bookmark"></i> حذف :
            <br/>
            <?php if (isset($office->officeFolders)): ?>
                <?php foreach ($office->officeFolders as $folder): ?>
                    <a href="/secretariat_v2/secretariat/delete-letter-folder?folder_id=<?= $folder->id ?>"><?= $folder->folder->name." "; ?></a>
                    <br/>
                <?php endforeach;?>
            <?php endif; ?>
            <div class="output-container">
                <?= $form->field($office, 'folders')->widget(\kartik\select2\Select2::classname(), [
                    'data' => \app\modules\secretariat_v2\models\OfficeFolderType::fetchAsDropDownArray(),
                    'options' => [
                        'placeholder' => 'انتخاب کنید...',
                        'multiple' => true,
                        'id' => 'folders_dropdown'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label("افزودن به پوشه") ?>
            </div>
        </div>
    </div>
    <div class="text-center">
        <?= Html::submitButton('ذخیره کن', ['class' => 'btn btn-primary btn-lg save-btn']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>
