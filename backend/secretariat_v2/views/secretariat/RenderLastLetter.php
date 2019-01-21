<?php
use yii\bootstrap\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;

$this->title = 'تبدیل نامه به خروجی';
$this->params['breadcrumbs'][] = 'دبیرخانه';
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
                <i class="fa fa-bookmark"></i> <?= $this->title ?>
            </div>
        </div>
        <div class="portlet-body">
            <table style="width:100%;direction: rtl">
                <tr>
                    <td style="width: 20%;">
                        <img src="/asset_admin/custom/img/logoc.png" style="width: 70px; height: 70px; margin-right: 38px;" />
                        <div style="margin-right: 20px;">بهار سامانه شرق</div>
                    </td>
                    <td style="width: 60%; text-align: center">
                        <h4 style="font-weight: bold"> به نام خدا </h4>
                    </td>
                    <td style="width: 20%; padding-top: 50px">
                        <div style="text-align: left">  تاربخ : <?= $letter_info['date'] ?>  </div>
                        <div style="text-align: left"> شماره نامه : -  </div>
                    </td>
                </tr>
            </table>

            <div style="width: 100%" >
                <div style="padding: 30px 20px 0px 20px; text-align: right; width: 100%">
                    <div style="font-weight: bold; font-size: 12pt; margin-bottom: 10px;"> دریافت کننده :   جناب <?= $letter_info['receiver'] ?> </div>
                    <div style="font-weight: bold; font-size: 12pt;"> مقام <?= $letter_info['occupation'] ?> </div>
                    <br/>
                    <br/>
                    <p style="font-weight: bold; font-size: 14pt; margin-bottom: 10px;"> عنوان :   <?= $letter_info['title'] ?> </p>

                </div>
            </div>
            <div style="width: 100%">
                <div style="padding: 10px 50px 0px 0px; text-align: right; width: 100%">
                    <p style=" font-size: 10pt;"> با سلام و احترام </p>

                </div>
            </div>
            <div class="editor">
                <?= $form->field($office_output, 'content')->widget(CKEditor::className(), [
                    'options' => ['rows' => 5],
                    'preset' => 'full',
                    'clientOptions' => [
                        'contentsLangDirection'=>'rtl',
                        'language' => 'fa',

                    ]
                ])->label('') ?>
            </div>


                <div style="width: 100%; text-align: left; padding-left: 250px; padding-top: 10px">
                    <h4 style="font-weight: bold; font-size: 12pt;">با تشکر </h4>
                    <h4 style="font-weight: bold; font-size: 12pt;"> <?= $letter_info['sender'] ?> </h4>
                </div>

                <hr/>
                <div class="row">
                    <div class="col-sm-12 text-left" style="direction: rtl;">
                        <div style="padding:10px; margin: 20px 0px">
                            <table style="float: left; font-size: 12px;">
                                <tr><td colspan="3"><span style="font-weight: bold">آدرس:</span> تهران، خیابان شیراز، کوچه ژاله، پلاک 6</td></tr>
                                <tr>
                                    <td><span style="font-weight: bold">کد پستی:</span> 53381-14369</td>
                                    <td><span style="font-weight: bold;">تلفن:</span> 42576000-021</td>
                                    <td><span style="font-weight: bold;">پست الکترونیکی:</span> info@bahar.network</td>
                                </tr>
                                <tr><td colspan="3">شرکت بهار سامانه شرق دارای مجوز servco به شماره پروانه 1009548 از سازمان تنظیم مقررات رادیویی</td></tr>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

        <div class="text-center">
            <?= Html::submitButton('  تبدیل نامه ', ['class' => 'btn btn-primary btn-lg save-btn']) ?>
        </div>
    <?php ActiveForm::end() ?>
</div>

