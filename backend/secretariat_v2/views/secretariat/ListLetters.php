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

$this->title = 'نامه ها';
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
    <div class="col-xs-12 col-sm-12">
        <div class="mt-checkbox-inline">
            <?php //TODO -> add permission here?>
            <form class="show-sub-form float-left">
                <label class="mt-checkbox show-sub <?= (isset($_GET['all']) && $_GET['all'] == '1') ? 'active' : '' ?>">
                    <input type="checkbox" name="all" value="1"  <?= (isset($_GET['all']) && $_GET['all'] == '1') ? 'checked="checked"' : '' ?>> <strong class="text-muted">نمایش تمام نامه ها</strong>
                    <span></span>
                </label>
            </form>
        </div>
    </div>
    <div class="col-xs-12">
        <?php
        Pjax::begin(['id' => 'mainGrid1']);
        echo GridView::widget([
            'dataProvider' => $officeLettersdataProvider,
            'filterModel' => $officeLettersSearch,
            'columns' => [
                [
                    'attribute' => '',
                    'label' => 'عملیات',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status_id == 1) {
                            return "<a href='/secretariat_v2/secretariat/view-letter?id=".$model->id."' ><span class='fa fa-cog fa-2x text-success direct'></span><a/>";
                        } else {
                            return "<a href='/secretariat_v2/secretariat/show-output-letter-code?office_id=".$model->id."' ><span class='fa fa-cog fa-2x text-success direct'></span><a/>";
                        }

                    },
                ],
                [
                    'label' => 'شماره نامه',
                    'attribute' => 'archive_number',
                    'format' => 'raw',
                    'value' => function($model) {
                        return "<div style='direction: ltr; text-align: center;'>".$model->archive_prefix. $model->archive_number."</div>";
                    }
                ],
                [
                    'label' => 'نوع نامه',
                    'filter' => \app\modules\secretariat_v2\models\OfficeType::fetchAsDropDownArray(),
                    'attribute' => 'type',
                    'format' => 'RAW',
                    'value' => function($model) {
                        return $model->officeType->id == 1 ? '<i class="fa fa-download text-success"></i>' : '<i class="fa fa-upload text-danger"></i>';
                    }
                ],
                [
                    'label' => 'وضعیت',
                    'filter' => \app\modules\secretariat_v2\models\OfficeStatus::fetchAsDropDownArray(),
                    'attribute' => 'status_id',
                    'format' => 'RAW',
                    'value' => function($model) {
                        if ($model->officeStatus != null) {
                            return $model->officeStatus->id == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-close text-danger"></i>';
                        }
                        return null;
                    }
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
                [
                    'label' => 'سازنده',
                    'value' => function($model) {
                        if(isset($model->created_by)){
                            return $model->createdBy->profile->name." ". $model->createdBy->profile->family;
                        }
                    }
                ],

            ],
        ]);
        Pjax::end();
        ?>
    </div>
</div>