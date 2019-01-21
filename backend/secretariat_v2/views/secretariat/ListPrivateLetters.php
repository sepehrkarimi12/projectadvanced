<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use app\models\Intldate;

$baseUrl = Url::home(true);

$this->title = 'نامه های شخصی';
$this->params['breadcrumbs'][] = 'دبیرخانه';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>

<div class="row">
    <?php if(Yii::$app->user->can('SecretariatMailIndexPerm')): ?>
        <div class="col-xs-12 col-md-2">
            <a href="<?= $baseUrl ?>secretariat_v2/mail/index" class="btn btn-md m-b-1 btn-primary"><i class="fa fa-download"></i> </span class="h4">دریافت  از ECE</span></a>
        </div>
    <?php endif ?>

    <div class="col-xs-12">
        <?php
        Pjax::begin(['id' => 'mainGrid1']);
        echo GridView::widget([
            'dataProvider' => $privateLettersdataProvider,
            'filterModel' => $privateLettersSearch,
            'columns' => [
                [
                    'attribute' => '',
                    'label' => 'عملیات',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status_id == 1) {
                            return "<a href='/secretariat_v2/secretariat/view-letter?id=".$model->id."' ><span class='fa fa-cog fa-2x text-success direct'></span><a/>";
                        } else {
                            return "<a href='/secretariat_v2/secretariat/show-output-letter-code?secretariat_id=".$model->id."' ><span class='fa fa-cog fa-2x text-success direct'></span><a/>";
                        }

                    },
                ],
                [
                    'label' => 'شماره نامه',
                    'attribute' => 'archive_number',
                    'value' => 'archive_number',
                ],
                [
                    'label' => 'نوع نامه',
                    'filter' => \app\modules\secretariat_v2\models\OfficeType::fetchAsDropDownArray(),
                    'attribute' => 'type',
                    'value' => function ($model) {
                        if (isset($model->officeType)) {
                            return $model->officeType->name;
                        }
                        return null;
                    },
                ],
                [
                    'label' => 'وضعیت',
                    'filter' => \app\modules\secretariat_v2\models\OfficeStatus::fetchAsDropDownArray(),
                    'attribute' => 'status_id',
                    'value' => function ($model) {
                        if ($model->officeStatus != null) {
                            return $model->officeStatus->name;
                        }
                        return null;
                    },
                ],
                [
                    'label' => 'عنوان نامه',
                    'attribute' => 'title',
                    'value' => 'title',
                ],
                [
                    'label' => 'تاریخ',
                    'attribute' => 'created_at',
                    'value'=> function ($dataProvider) {
                        $intldate = new Intldate;
                        return $intldate->timestampToPersian($dataProvider->created_at);
                    },
                ],
                [
                    'label' => 'سطح دسترسی',
                    'filter' => \app\modules\secretariat_v2\models\OfficeAccessLevel::fetchAsDropDownArray(),
                    'attribute' => 'access_level',
                    'value' => function ($model) {
                        if (isset($model->accessLevel)) {
                            return $model->accessLevel->name;
                        }
                        return null;
                    },
                ],
                [
                    'label' => 'اولویت',
                    'filter' => \app\modules\secretariat_v2\models\OfficePriority::fetchAsDropDownArray(),
                    'attribute' => 'priority',
                    'value' => function ($model) {
                        if (isset($model->priority)) {
                            return $model->priority->name;
                        }
                        return null;
                    },
                ],
                [
                    'label' => 'دسته بندی',
                    'filter' => \app\modules\secretariat_v2\models\OfficeCategory::fetchAsDropDownArray(),
                    'attribute' => 'category',
                    'value' => function ($model) {
                        if (isset($model->category)) {
                            return $model->category->name;
                        }
                        return null;
                    },
                ],
                [
                    'label' => 'وضعیت بایگانی',
                    'filter' => array(0 => 'بایگانی نشده',1 => 'بایگانی شده'),
                    'attribute' => 'is_archived',
                    'value' => function($model) {
                        if($model->is_archived == 1){
                            return "بایگانی شده";
                        } else {
                            return " بایگانی نشده";
                        }

                    }

                ],

            ],
        ]);
        Pjax::end();
        ?>
    </div>
</div>