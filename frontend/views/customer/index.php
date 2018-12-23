<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\searchs\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Customers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Customer'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'fname',
            'lname',
            'address',
            'email:email',
            //'phone',
            //'mobile',
            //'is_deleted',
            //'creator_id',
            //'created_at',
            //'deletor_id',
            //'deleted_at',

            // ['class' => 'yii\grid\ActionColumn'],

            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'additional_icon' => function ($url, $model, $key) {
                        return Html::a ( '<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> ', ['comment/create', 'id' => $model->id, 'lname' => $model->lname] );
                    },
                ],
                'template' => '{update} {view} {delete} {additional_icon}'


            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
