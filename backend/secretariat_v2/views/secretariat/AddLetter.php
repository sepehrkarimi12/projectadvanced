<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'افزودن نامه';
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
            <div class="row">
                <div class="col-sm-12 check-category">
                    <?= $form->field($office_main, 'category_id')->widget(\kartik\select2\Select2::classname(), [
                        'data' => \app\modules\secretariat_v2\models\OfficeCategory::fetchAsDropDownArray(),
                        'options' => [
                            'placeholder' => 'دسته بندی را انتخاب کنید',
                            'multiple' => false,
                            'id' => 'officemain-category'
                        ],])->label('دسته بندی نامه') ?>
                </div>
                <div class="col-sm-12">
                    <div class="category-container none">
                        <?= $form->field($office_main, 'secretariat_numbers')->widget(\kartik\select2\Select2::classname(), [
                            'data' => \app\modules\secretariat_v2\models\OfficeMain::getOfficeNumbers(),
                            'options' => [
                                'placeholder' => 'انتخاب کنید...',
                                'multiple' => false,
                                'id' => 'numbers_dropdown'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label("شماره نامه را وارد کنید") ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <input type="text" name="OfficeMain[letter_date]" class="form-control ltr-all" id="end_date" placeholder="تاریخ نامه" data-mddatetimepicker="true" data-trigger="click" data-targetselector="#end_date" data-groupid="group1" data-enabletimepicker="true" data-placement="bottom" autocomplete="false" readonly="readonly" data-englishnumber="true" data-todate="true"/><br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <?= $form->field($office_main, 'office_type_id')->widget(\kartik\select2\Select2::classname(), [
                        'data' => \app\modules\secretariat_v2\models\OfficeType::fetchAsDropDownArray(),
                        'options' => [
                            'placeholder' => 'نوع نامه را انتخاب کنید',
                            'multiple' => false,
                            'id' => 'secretariat-type'
                        ],
                        ])->label('نوع نامه') ?>
                </div>
                <div class="col-sm-4">
                    <div class="input-container">
                        <?= $form->field($office_main, 'secretariat_number')->textInput(['class'=>'form-control text-center ltr'])->label('شماره نامه') ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="input-container">
                        <?= $form->field($office_main->uploads, 'photo[]')->fileInput(['multiple' => true, 'class'=>'form-control', 'required' => false])->label('تصویر نامه') ?>
                    </div>
                </div>
            </div>

            <?= $form->field($office_main, 'title')->textInput(['required'=> true])->label('موضوع نامه') ?>

            <?= $form->field($office_main, 'access_level_id')->widget(\kartik\select2\Select2::classname(), [
                'data' => \app\modules\secretariat_v2\models\OfficeAccessLevel::fetchAsDropDownArray(),
                'options' => [
                    'placeholder' => 'سطح دسترسی را انتخاب کنید',
                    'multiple' => false,
                ],])->label('سطح دسترسی') ?>


            <?= $form->field($office_main, 'priority_id')->widget(\kartik\select2\Select2::classname(), [
                'data' => \app\modules\secretariat_v2\models\OfficePriority::fetchAsDropDownArray(),
                'options' => [
                    'placeholder' => 'اولویت را انتخاب کنید',
                    'multiple' => false,
                    'id' => 'officemain-priority'
                ],
                ])->label('اولویت نامه') ?>

            <?php
            for($i=1; $i<=31;$i++) { $day[$i] = $i; }
            for($i=1; $i<=12;$i++) { $month[$i] = $i; }
            for($i=$current_date['year']; $i<=($current_date['year']+5); $i++) { $year[$i] = $i; }
            ?>
            <div class="input-container">
                <div class="row priority-deadline none">
                    <div class="col-xs-12 col-md-3 p-a-1">
                        <div class="bold p-a-1">مهلت رسیدگی</div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?= $form->field($office_main, 'start_day')->dropDownList($day, ['required'=> true, 'value'=> $current_date['day']])->label('روز') ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?= $form->field($office_main, 'start_month')->dropDownList($month, ['required'=> true, 'value'=> $current_date['month']])->label('ماه') ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?= $form->field($office_main, 'start_year')->dropDownList($year, ['required'=> true, 'value'=> $current_date['year']])->label('سال') ?>
                    </div>
                </div>
            </div>

            <?= $form->field($office_main, 'sender_id')->widget(\kartik\select2\Select2::classname(), [
                'data' => \app\modules\secretariat_v2\models\OfficePeople::fetchAsDropDownArray(),
                'options' => [
                    'placeholder' => 'انتخاب کنید...',
                    'multiple' => false,
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label("فرستنده (از طرف)") ?>
            <div class="output-container none">
                <?= $form->field($office_main, 'actionator_id')->widget(\kartik\select2\Select2::classname(), [
                    'data' => \app\modules\secretariat_v2\models\OfficePeople::fetchAsDropDownArray(    ),
                    'options' => [
                        'placeholder' => 'انتخاب کنید...',
                        'multiple' => false,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label("اقدام کننده") ?>
            </div>
            <div class="output-container none">
                <?= $form->field($office_main, 'receiver_id')->widget(\kartik\select2\Select2::classname(), [
                    'data' => \app\modules\secretariat_v2\models\OfficePeople::fetchAsDropDownArray(),
                    'options' => [
                        'placeholder' => 'انتخاب کنید...',
                        'multiple' => false,
                        'id' => 'reciver_dropdown'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label("گیرنده") ?>
            </div>
            <?= $form->field($office_main, 'folders')->widget(\kartik\select2\Select2::classname(), [
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

            <?= $form->field($office_main, 'assigned_person')->widget(\kartik\select2\Select2::classname(), [
                'data' => \app\modules\manage\models\User::getSecretariatEmployees(),
                'options' => [
                    'placeholder' => 'انتخاب کنید...',
                    'multiple' => false,
                    'id' => 'employees_dropdown'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label("ارجاع") ?>

            <?= $form->field($office_main, 'description')->textArea(['required'=> true])->label('توضیحات') ?>
            <div class="row">
                <div class="col-sm-12 p-t-fix">
                    <label for="check-attachment"><input id="check-attachment" type="checkbox" name="checkbox" value="value"> پیوست دارد</label>
                    <div class="attachment-container none">
                        <?= $form->field($office_main->attachments, 'photos[]')->fileInput(['multiple' => true, 'class'=>'form-control', 'required' => false])->label('تصویر رونوشت') ?>
                    </div>
                </div>
            </div>

            <label for="check-transcript"><input id="check-transcript" type="checkbox" name="checkbox" value="value"> رونوشت دارد</label>
            <div class="transcript-container none">
                <?= $form->field($office_main, 'transcripts')->widget(\kartik\select2\Select2::classname(), [
                    'data' => \app\modules\secretariat_v2\models\OfficePeople::fetchAsDropDownArray(),
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
    </div>
</div>
<div class="text-center">
    <?= Html::submitButton('ذخیره کن', ['class' => 'btn btn-primary btn-lg save-btn']) ?>
</div>
<?php ActiveForm::end() ?>
</div>

<?php
$js=<<<JS
$('#officemain-priority').change(function() {
    if ($(this).val() != 1) {
        $('.priority-deadline').slideDown();
    } else {
        $('.priority-deadline').slideUp();
    }
});

$("#secretariat-type").change(function() {
    
    var type = $(this).val();
    $('.isold-container').hide();
    $("#officemain-is_old").prop('checked', false);

    if (type == 2) {

        $('.output-container').show();
        $('.input-container').hide();
        $('#secretariatupload-photo').prop('required', false);

    } else if(type == 1) {

        $('.input-container').show();
        $('.output-container').hide();
        $('#secretariatupload-photo').prop('required', true);
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

$('#officemain-category').change(function() {
    var selected_type = $(this).val();
    if (selected_type == 1) {
        $('.category-container').hide();
    } else {
        $('.category-container').show(); 
    }
});
JS;
$this->registerJS($js);

$disableBtn=<<<JS
$(function() {
    // $('#w0').on('afterValidate', function (e) {
    //     $('.save-btn').prop('disabled', true);
    //     return true;
    // });
});
JS;
$this->registerJS($disableBtn);
?>
