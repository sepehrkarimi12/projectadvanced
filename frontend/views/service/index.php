<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\searchs\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Services');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Service'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'name',
            [
                'attribute' => 'customer_id',
                'value' => function($model){
                    return $model->customer->fname.' '.$model->customer->lname;
                }
            ],
            [
                'attribute' => 'type_id',
                'value' => function($model){
                    return $model->type->title;
                }
            ],
            [
                'attribute' => 'network_id',
                'value' => function($model){
                    return $model->network->name;
                }
            ],
            'address',
            //'ppoe_username',
            //'ppoe_password',
            //'is_deleted',
            //'creator_id',
            //'created_at',
            //'deletor_id',
            //'deleted_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
