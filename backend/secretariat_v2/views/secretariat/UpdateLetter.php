<?php

use app\modules\secretariat_v2\models\OfficePeople;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'ویرایش نامه';
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
                <i class="fa fa-bookmark"></i>ویرایش نامه
            </div>
        </div>
        <div class="portlet-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php if($office_main->category->name != 'جدید'):?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->field($office_main, 'category')->textInput(['value' => $office_main->category->name, 'disabled'=>'disabled'])->label('گروه بندی نامه') ?>
                                </div>
                                <div class="col-sm-6 p-t-fix">
                                    <?= $form->field($office_main, 'relation_number')->textInput(['value' => \app\modules\secretariat_v2\repositories\OfficeRepository::findRelationNumber($office_main), 'disabled'=>'disabled'])->label(false) ?>
                                </div>
                            </div>
                        <?php else:?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= $form->field($office_main, 'category')->textInput(['value'=> $office_main->category->name, 'disabled'=>'disabled'])->label('گروه بندی نامه') ?>
                                </div>
                            </div>
                        <?php  endif; ?>
                        <?php if($office_main->officeType->name == 'ورودی'): ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= $form->field($office_main, 'office_type_id')->textInput(['value'=> $office_main->officeType->name ,'disabled'=>'disabled'])->label('نوع نامه') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                <?= $form->field($office_main, 'secretariat_number')->textInput(['class'=>'form-control text-center ltr'])->label('شماره نامه') ?>
                                </div>
                                <div class="col-sm-6">
                                <?= $form->field($office_main->uploads, 'photo[]')->fileInput(['multiple' => true, 'class'=>'form-control', 'required' => false])->label('تصویر نامه') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                <?= $form->field($office_main, 'title')->textInput(['required'=> true])->label('موضوع نامه') ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($office_main, 'access_level_id')->widget(\kartik\select2\Select2::classname(), [
                                        'data' => \app\modules\secretariat_v2\models\OfficeAccessLevel::fetchAsDropDownArray(),
                                        'options' => [
                                            'placeholder' => 'انتخاب کنید...',
                                            'multiple' => false,
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label(" سطح دسترسی") ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->field($office_main, 'priority_id')->widget(\kartik\select2\Select2::classname(), [
                                        'data' => \app\modules\secretariat_v2\models\OfficePriority::fetchAsDropDownArray(),
                                        'options' => [
                                            'placeholder' => 'انتخاب کنید...',
                                            'multiple' => false,
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label('اولویت نامه') ?>
                                </div>
                                <div class="col-sm-6">
                                <?= $form->field($office_main, 'sender_id')->widget(\kartik\select2\Select2::classname(), [
                                    'data' => OfficePeople::fetchAsDropDownArray(),
                                    'options' => [
                                        'placeholder' => 'انتخاب کنید...',
                                        'multiple' => false,
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("فرستنده (از طرف)") ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                <?= $form->field($office_main, 'description')->textArea(['required'=> true])->label('توضیحات') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php if($office_main->officeAttachments != null): ?>
                                        <label for="check-attachment"><input id="check-attachment" type="checkbox" name="checkbox" value="value" checked="checked"> پیوست دارد</label>
                                        <div class="attachment-container">
                                            <?= $form->field($office_main->attachments, 'photos[]')->fileInput(['multiple' => true, 'class'=>'form-control', 'required' => false])->label('افزودن تصویر پیوست') ?>
                                        </div>
                                    <?php else: ?>
                                        <label for="check-attachment"><input id="check-attachment" type="checkbox" name="checkbox" value="value"> پیوست دارد</label>
                                        <div class="attachment-container none">
                                            <?= $form->field($office_main->attachments, 'photos[]')->fileInput(['multiple' => true, 'class'=>'form-control', 'required' => false])->label('افزودن تصویر پیوست') ?>
                                        </div>
                                    <?php endif; ?>
                                        <span>حذف: </span><br>
                                    <?php foreach ($attachments as $attachment): ?>
                                        <a href="update-letter?id=<?= $office_main->id ?>&action=delete&attachment_id=<?= $attachment->id ?>"> <?= $attachment->path ?> </a><br>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if($office_main->officeType->name =='خروجی'): ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= $form->field($office_main, 'office_type_id')->textInput(['value'=> $office_main->officeType->name ,'disabled'=>'disabled'])->label('نوع نامه') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= $form->field($office_main, 'archive_number')->textInput(['value'=> $office_main->archive_number ,'disabled'=>'disabled'])->label('شماره نامه') ?>
                                </div>
                                <div class="col-sm-6 p-t-fix">
                                    <?= $form->field($office_main, 'is_old')->checkbox(['disabled'=>'disabled'])->label(' نامه قدیمی است '); ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($office_main->uploads, 'photo[]')->fileInput(['multiple' => true, 'class'=>'form-control', 'required' => false])->label('تصویر نامه') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= $form->field($office_main, 'title')->textInput(['required'=> true])->label('موضوع نامه') ?>
                                </div>
                                <div class="col-sm-12">
                                    <?= $form->field($office_main, 'access_level_id')->widget(\kartik\select2\Select2::classname(), [
                                        'data' => \app\modules\secretariat_v2\models\OfficeAccessLevel::fetchAsDropDownArray(),
                                        'options' => [
                                            'placeholder' => 'انتخاب کنید...',
                                            'multiple' => false,
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label(" سطح دسترسی") ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= $form->field($office_main, 'priority_id')->widget(\kartik\select2\Select2::classname(), [
                                        'data' => \app\modules\secretariat_v2\models\OfficePriority::fetchAsDropDownArray(),
                                        'options' => [
                                            'placeholder' => 'انتخاب کنید...',
                                            'multiple' => false,
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label('اولویت نامه') ?>
                                </div>
                                <div class="col-sm-12">
                                    <?= $form->field($office_main, 'sender_id')->widget(\kartik\select2\Select2::classname(), [
                                        'data' => OfficePeople::fetchAsDropDownArray(),
                                        'options' => [
                                            'placeholder' => 'انتخاب کنید...',
                                            'multiple' => false,
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label("فرستنده (از طرف)") ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= $form->field($office_main, 'actionator_id')->widget(\kartik\select2\Select2::classname(), [
                                        'data' => OfficePeople::fetchAsDropDownArray(),
                                        'options' => [
                                            'placeholder' => 'انتخاب کنید...',
                                            'multiple' => false,
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label("اقدام کننده") ?>
                                </div>
                                <div class="col-sm-12">
                                    <?= $form->field($office_main, 'receiver_id')->widget(\kartik\select2\Select2::classname(), [
                                        'data' => OfficePeople::fetchAsDropDownArray(),
                                        'options' => [
                                            'placeholder' => 'انتخاب کنید...',
                                            'multiple' => false,
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label("گیرنده") ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= $form->field($office_main, 'description')->textArea(['required'=> true])->label('توضیحات') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php if($office_main->officeAttachments != null): ?>
                                        <label for="check-attachment"><input id="check-attachment" type="checkbox" name="checkbox" value="value" checked="checked"> پیوست دارد</label>
                                        <div class="attachment-container">
                                            <?= $form->field($office_main->attachments, 'photos[]')->fileInput(['multiple' => true, 'class'=>'form-control', 'required' => false])->label('تصویر پیوست') ?>
                                        </div>
                                    <?php else: ?>
                                        <label for="check-attachment"><input id="check-attachment" type="checkbox" name="checkbox" value="value"> پیوست دارد</label>
                                        <div class="attachment-container none">
                                            <?= $form->field($office_main->attachments, 'photos[]')->fileInput(['multiple' => true, 'class'=>'form-control', 'required' => false])->label('تصویر پیوست') ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($attachments != null): ?>
                                        <span>حذف: </span><br>
                                    <?php endif; ?>
                                    <?php foreach ($attachments as $attachment): ?>
                                        <a href="update-letter?id=<?= $office_main->id ?>&action=delete&attachment_id=<?= $attachment->id ?>"><?= $attachment->path ?></a><br>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php if(\app\modules\secretariat_v2\repositories\OfficeRepository::letterTranscripts($office_main->id) != array()){?>
                                        <label for="check-transcript"><input id="check-transcript" type="checkbox" name="checkbox" value="value" checked="checked"> رونوشت دارد</label>
                                        <div class="transcript-container">
                                            <?= $form->field($office_main, 'transcript')->widget(\kartik\select2\Select2::classname(), [
                                                'data' => OfficePeople::fetchAsDropDownArray(),
                                                'options' => [
                                                    'value'=> \app\modules\secretariat_v2\repositories\OfficeRepository::letterTranscripts($office_main->id, true),
                                                    'placeholder' => 'انتخاب کنید...',
                                                    'multiple' => true,
                                                    'id' => 'transcript_dropdown'
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ])->label("رو نوشت به") ?>
                                        </div>
                                    <?php }else{ ?>
                                        <div class="output-container">
                                            <label for="check-transcript"><input id="check-transcript" type="checkbox" name="checkbox" value="value"> رونوشت دارد</label>
                                            <div class="transcript-container none">
                                                <?= $form->field($office_main, 'transcript')->widget(\kartik\select2\Select2::classname(), [
                                                    'data' => OfficePeople::fetchAsDropDownArray(),
                                                    'options' => [
                                                        'placeholder' => 'انتخاب کنید...',
                                                        'multiple' => true,
                                                        'id' => 'transcript_dropdown'
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                ])->label("رو نوشت به") ?>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <?= Html::submitButton('ذخیره کن', ['class' => 'btn btn-primary btn-lg save-btn']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>

<?php
$js=<<<JS
$('#secretariat-priority').change(function(){
    if ($(this).val() != 'عادی') {
        $('.priority-deadline').slideDown();
    } else {
        $('.priority-deadline').slideUp();
    }
});

$("#secretariat-type").click(function() {
    
    var type = $(this).val();
    $('.isold-container').hide();
    $("#secretariat-is_old").prop('checked', false);

    if (type == 'خروجی') {

        $('.output-container').show();
        $('.input-container').hide();
        $('#secretariatupload-photo').prop('required', false);

    } else if(type == 'ورودی') {

        $('.input-container').show();
        $('.output-container').hide();
        $('#secretariatupload-photo').prop('required', true);
    }
});

$("#secretariat-is_old").click(function(){
   
    if ($(this).is(':checked')){
        $(".isold-container").show(); 
    } else {
        $(".isold-container").hide(); 
    }   
});

$("#check-transcript").click(function(){
   
    if ($("#check-transcript").is(':checked')){
        $(".transcript-container").show(); 
    } else {
        $(".transcript-container").hide(); 
    }   
});

$("#check-attachment").click(function(){
    if ($("#check-attachment").is(':checked')){
        $(".attachment-container").show(); 
    } else {
        $(".attachment-container").hide(); 
    }   
});
JS;
$this->registerJS($js);

$disableBtn=<<<JS
$(function() {
    $('#w0').on('afterValidate', function (e) {
        $('.save-btn').prop('disabled', true);
        return true;
    });
});
JS;
$this->registerJS($disableBtn);
?>