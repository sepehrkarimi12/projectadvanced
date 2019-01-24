<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = 'افزودن LTE اشتراکی به مشتری';
$this->params['breadcrumbs'][] = 'مشتریان';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>
<div class="col-sm-8 col-sm-offset-2">
    <?php $form = ActiveForm::begin() ?>
    <div class="portlet grey-silver box">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i> اطلاعات عمومی مشتری
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <label>نام:</label>
                    <span class="bold text-info"><?=$user_result->profile->name ?></span>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <label>نام خانوادگی:</label>
                    <span class="bold text-info"><?= $user_result->profile->family ?></span>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <label>نام پدر:</label>
                    <span class="bold text-info"><?=  $user_result->profile->father_name ?></span>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <label>شماره ملی:</label>
                    <span class="bold text-info"><?= $user_result->profile->id_card ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="portlet grey-silver box">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark"></i> اطلاعات محصول مشتری
            </div>
        </div>

        <div class="portlet-body">

            <p class="bold text-center">ابتدا نوع محصول را انتخاب کنید</p>
            <div class="tabbable tabbable-tabdrop">
                <div class="none"><?= $form->field($service, 'platform')->textInput(['required'=> true])->label(false) ?></div>
                <ul class="nav nav-pills service_tab_type">
                    <li class="tabs-select" style="width: 49.5%;">
                        <a href="#tab1" class="bold text-center" data-toggle="tab" aria-expanded="false" data-type="اینترنت">اینترنت</a>
                    </li>
                    <li class="tabs-select" style="width: 49.5%;">
                        <a href="#tab2" class="bold text-center" data-toggle="tab" aria-expanded="false" data-type="اینترانت (ارتباط داخلی)">اینترانت (ارتباط داخلی)</a>
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

        <div class="portlet-body form_input none">

            <?= $form->field($lte, 'input_mobile_number')->input('number',['required'=> true, 'id' => 'mobile_number'])->label('شماره تلفن همراه مورد درخواست برای سیمکارت') ?>
            <p class="phone-error none alert alert-danger"> </p>
            <p class="phone-success none alert alert-info">  </p>
            <?= $form->field($service, 'postal_code')->input('number', ['required' => true])->label('کد پستی') ?>
            <?= $form->field($service, 'address')->input('text', ['required' => true])->label('آدرس') ?>

            <hr>

            <?php if ($factor_product_maker == []): ?>
                <p class="alert alert-info">هزینه ی خدماتی اختصاص داده نشده است.</p>
            <?php endif; ?>
            <label class="control-label">هزینه خدمات</label>
            <?php
                foreach ($factor_product_maker as $key => $value) {
                    //echo $form->field($product_maker_factor_log, 'selected_factors['.$key.']')->checkboxList([$key => $value['price']])->label(false);
                    echo $form->field($product_maker_factor_log, 'selected_factors['.$key.']')->checkboxList([$key => $value['price']], [
                          'itemOptions' => $value['is_optional'] ? ['disabled' => 'disabled'] : []
                    ])->label(false);
                }
            ?>
        </div>
    </div>
    <div class="text-center send-request">
        <?= Html::submitButton('ذخیره کن', ['class' => 'btn btn-primary btn-lg save-btn']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>

<?php
$js=<<<JS
$(".send-request").hide();
$(".phone-error").hide();
$(".phone-success").hide();

$(function() {
    $('#mobile_number').keyup(function(){
		var client_data = { 
			mobile: $(this).val()
		};

		$.ajax({
            url: '/lte/service/check-phone-availability-ajax',
            type: 'POST',
            dataType: 'json',
            data: client_data,
            error: function (e) {
                
            },
            success: function (data) {
                if (data["statusCode"] == 0) {
                    $(".send-request").show();
                    $(".phone-error").hide();
                    $(".phone-success").html(data["statusMessage"]).show();
                } else {
                    $(".send-request").hide();
                    $(".phone-error").html(data["statusMessage"]).show();
                    $(".phone-success").hide();
                }
            },
        });
	});

	$('input[required], textarea, select').on('invalid', function(){
		return this.setCustomValidity('این فیلد الزامی است.');
	}).on('input', function(){
		return this.setCustomValidity('');
	});

	// adding service type from tabs to postable input
	$('#service-platform').val($('.service_tab_type li:first a').data('type'));
	$('.service_tab_type li a').on('click', function(){
		var thisService = $(this).data('type');
		$('#service-platform').val(thisService);
		$('.tab-content .tab-pane').find('input').prop('required', false);
		var currentTab = $(this).attr('href').substring(1);
		if(currentTab=='tab1') {
			$('#'+currentTab).find('input').prop('required', true);
		}
		$('.form_input').slideDown();
	});


	$('#w0').on('afterValidate', function (e) {
	    $('.save-btn').prop('disabled', true);
	    return true;
	});
});
JS;
$this->registerJS($js);


$buildingAjaxJs=<<<JS
$(function() {

	$('.tower_type').change(function(){
		var tower_type = $(this);
		if(tower_type.val() == 'building'){
			tower_type.parent().find('.building_wrapper').show();
			tower_type.parent().find('.point_wrapper').hide();
		} else if(tower_type.val() == 'point') {
			tower_type.parent().find('.building_wrapper').hide();
			tower_type.parent().find('.point_wrapper').show();
		}
	});

	var service_type = $('.service-type');
	var building_dropdown = $('.building_dropdown');
	var radio_dropdown = $('.radio_dropdown');

	$('.building_dropdown, .point_dropdown').change(function() {

		var thisElement = $(this);

		var station = { 
			tower_type: thisElement.data('tower_type'),
			station_id: thisElement.val(),
			service_type: service_type.val(),
		};

		$.ajax({
            url: '/manage/customer/selected-service-radios',
            type: 'POST',
            dataType: 'json',
            data: station,
            error: function (e) {console.log(e.error)},
            success: function (data) {
                if(!$.isEmptyObject(data)) {
                    var html ='';
                    html += '<option value="">انتخاب کنید...</option>';
                    $.each(data, function(index, value){
                        html += '<option value="'+value.id+'">'+value.radio_name+'</option>';
                    });
                    thisElement.parent().parent().find('.radio_dropdown').html(html);
                    thisElement.parent().parent().find('.radio_dropdown').prop('disabled', false);

                } else {
                	thisElement.parent().parent().find('.radio_dropdown').html('');
                    thisElement.parent().parent().find('.radio_dropdown').prop('disabled', true);
                }
            },
        });
	});
});
JS;
$this->registerJS($buildingAjaxJs);

?>
