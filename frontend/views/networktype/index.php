<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\searchs\NetworktypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Networktypes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="networktype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Networktype'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'title',
            // 'is_deleted',
            [
                'attribute' => 'creator_id',
                'value' => function($data) {
                    return $data->creator->username;
                }
            ],
            [
                'attribute' => 'need_ip',
                'value' => function($data) {
                    return $data->need_ip ? 'Yes' : 'No';
                },
                'filter' => [1 => 'Yes', 0 => 'No'],
                'filterInputOptions' => [
                   'class' => 'form-control',         
                   'prompt' => 'All'
                ],
            ],
            // 'created_at',
            //'deletor_id',
            //'deleted_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
