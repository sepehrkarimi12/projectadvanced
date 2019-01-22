<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use app\models\Intldate;
use PAMI\Message\Action\MeetmeListAction;
use app\models\MessageHandler;
use yii\debug\models\timeline\DataProvider;

$baseUrl = Url::home(true);

$this->title = 'ویرایش کاراکتر';
$this->params['breadcrumbs'][] = 'دبیرخانه';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>
    <div class="col-sm-8 col-sm-offset-2">
        <div class="portlet grey-silver box">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bookmark"></i> <?= $this->title ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?= $form->field($office_prefix, 'name')->textInput(['class'=>'form-control', 'maxlength' => 1])->label('کاراکتر') ?>
                        <?= $form->field($office_prefix, 'description')->textArea()->label('توضیحات') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <?= Html::submitButton('ذخیره کن', ['class' => 'btn btn-primary btn-lg save-btn']) ?>
    </div>
<?php ActiveForm::end() ?>