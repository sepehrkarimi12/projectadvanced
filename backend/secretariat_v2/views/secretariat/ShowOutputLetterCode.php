<?php
use dosamigos\ckeditor\CKEditor;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'نامه های بدون تصویر';
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
                <i class="fa fa-bookmark"></i>افزودن نامه
            </div>
        </div>
        <div class="portlet-body">
            <div class="alert alert-info text-center h4 rtl">
                شماره نامه
                <br><br>
                <span class="p-a-1 h2 bold">
                    <?= $office_main->archive_number ?>
                </span>
            </div>
            <div class="portlet-body">

                <p class="bold text-center">ابتدا نوع تایید را انتخاب کنید </p>
                <div class="tabbable tabbable-tabdrop">
                    <div class="none"></div>
                    <ul class="nav nav-pills service_tab_type">
                        <li class="tabs-select type" style="width: 49.5%;">
                            <a href="#tab1" class="bold text-center letter" data-toggle="tab" aria-expanded="false" data-type="m"> دستی </a>
                        </li>
                        <li class="tabs-select type" style="width: 49.5%;">
                            <a href="#tab2" class="bold text-center letter" data-toggle="tab" aria-expanded="false" data-type="s"> سیستمی </a>
                        </li>
                    </ul>
                    <div class="tab-content form_input none">
                        <div id="tab1" class="tab-pane fade">
                        </div>
                        <div id="tab2" class="tab-pane fade">
                        </div>
                    </div>
                </div>
            </div>
            <div class=" images">
                <?= $form->field($secretariat_upload, 'photo[]')->fileInput(['multiple' => true, 'class'=>'form-control'])->label('تصویر نامه') ?>

                <?= $form->field($office_main, 'title')->textInput(['readOnly' => true])->label('موضوع نامه') ?>

                <?= $form->field($office_main, 'access_level_id')->widget(\kartik\select2\Select2::classname(), [
                    'data' => \app\modules\secretariat_v2\models\OfficeAccessLevel::fetchAsDropDownArray(),
                    'options' => [
                        'placeholder' => 'انتخاب کنید...',
                        'disabled' => true,
                        'multiple' => false,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(" سطح دسترسی") ?>

                <?= $form->field($office_main, 'priority_id')->widget(\kartik\select2\Select2::classname(), [
                    'data' => \app\modules\secretariat_v2\models\OfficePriority::fetchAsDropDownArray(),
                    'options' => [
                        'placeholder' => 'انتخاب کنید...',
                        'multiple' => false,
                        'disabled' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('اولویت نامه') ?>

                <?= $form->field($office_main, 'receiver_name')->textInput(['readOnly' => true, 'value' => $receiver])->label('گیرنده') ?>
                <?= $form->field($office_main, 'sender_name')->textInput(['readOnly' => true, 'value' => $sender])->label('فرستنده') ?>
                <?= $form->field($office_main, 'actionator_name')->textInput(['readOnly' => true, 'value' => $actionator])->label('اقدام کننده') ?>
                <?= $form->field($office_main, 'description')->textArea(['readOnly' => true])->label('توضیحات') ?>

                <hr>

                <?php
                if ($secretariat_transcripts != null) {
                    echo '<label class="control-label"> رونوشت </label><br>';
                    foreach ($secretariat_transcripts as $key => $transcript) {
                        echo $transcript." <br>";
                    }
                } else {

                    echo "<div class='alert alert-info'>رونوشت ندارد.</div>";
                }
                ?>
            </div>
            <div class="editor">
                <?= $form->field($office_output, 'content')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6, 'placeholder' => 'نامه'],
                    'preset' => 'full',
                    'clientOptions' => [
                        'contentsLangDirection'=>'rtl',
                        'language' => 'fa',

                    ]
                ])->label('نامه') ?>
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
    $('.images').hide();
    $('.editor').hide();
    $('.letter').click(function() {
        var result =  $(this).data('type');
        if ( result == 'm') {
            $('.images').show();
            $('.editor').hide();
        } else if(result == 's') {
            $('.images').hide();
            $('.editor').show();
        }
    });
});
JS;
$this->registerJS($js);
?>