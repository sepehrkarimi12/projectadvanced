<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\searchs\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Comments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Comment'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($row) {
            // return $row->text;
            // return $row->text = 21;
            // die;
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'text:ntext',
            [
                'attribute' => 'text',
                'format' => 'ntext',
                'value' => function($data) {
                    return html_entity_decode($data->text);
                }
            ],
            // 'file',
            [
                'attribute' => 'customer_id',
                'value' => function($data) {
                    return $data->customer->lname;
                }
            ],
            // 'is_deleted',
            [
                'attribute' => 'creator_id',
                'value' => function($data) {
                    return $data->creator->username;
                }
            ],
            // 'is_deleted',
            //'creator_id',
            // 'created_at:date',
            //'deletor_id',
            //'deleted_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
