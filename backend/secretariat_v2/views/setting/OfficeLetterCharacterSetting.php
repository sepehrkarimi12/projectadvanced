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

$this->title = 'تنظیمات شماره نامه';
$this->params['breadcrumbs'][] = 'دبیرخانه';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1 class="page-title"><?= $this->title ?>
    <small></small>
</h1>

<div class="row">
    <div class="col-xs-12">
        <?php
        Pjax::begin(['id' => 'mainGrid1']);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => '',
                    'label' => 'عملیات',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $actionMenu = [
                            [
                                ['category_title' => ''],
                                [
                                    "url" => "/secretariat_v2/setting/office-set-letter-character?id=".$model->id,
                                    "text" => "تنظیم کاراکتر نامه",
                                    "icon" => "fa fa-download",
                                    "color" => "green-jungle",
                                    "is_ajax" => false,
                                    "id"=>$model->id
                                ],
                            ],
                            [
                                ['category_title' => ''],
                                [
                                    "url" => "/secretariat_v2/setting/office-set-letter-format?id=".$model->id,
                                    "text" => "تنظیم فرمت نامه",
                                    "icon" => "fa fa-download",
                                    "color" => "green-jungle",
                                    "is_ajax" => false,
                                    "id"=>$model->id
                                ],
                            ],
                        ];
                        $actionMenu = json_encode($actionMenu);
                        return "<span class='fa fa-th fa-2x text-success' data-items='" . $actionMenu . "'></span>";
                    },
                ],
                [
                    'label' => 'نوع نامه',
                    'attribute' => 'name',
                    'value' => 'name',
                ],
                [
                    'label' => 'کاراکتر',
                    'attribute' => 'character_id',
                    'value' => function($model) {
                        if ($model->character != null) {
                            return $model->character->name;
                        }
                        return null;
                    }
                ],
                [
                    'label' => 'فرمت',
                    'attribute' => 'format_id',
                    'value' =>  function($model) {
                        if ($model->format != null) {
                            return $model->format->name;
                        }
                        return null;
                    }
                ],
                [
                    'label' => 'تاریخ ایجاد',
                    'attribute' => 'created_at',
                    'value' => function($model) {
                        if ($model->created_at != null) {
                            return Intldate::get()->timestampToPersian($model->created_at);
                        }
                        return null;
                    }
                ],
                [
                    'label' => 'تاریخ آخرین بروزرسانی',
                    'attribute' => 'updated_at',
                    'value' => function($model) {
                        if ($model->updated_at != null) {
                            return Intldate::get()->timestampToPersian($model->updated_at);
                        }
                        return null;
                    }
                ],
            ],
        ]);
        Pjax::end();
        ?>
    </div>
</div>