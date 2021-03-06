<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

// echo"<pre>";print_r($model);die();
/* @var $this yii\web\View */
/* @var $model frontend\models\Customer */

$this->title = $model->lname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('app', 'ADD Comment'),['comment/create', 'id' => $model->id],
         ['class'=>'btn btn-default']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'fname',
            'lname',
            'address',
            'email:email',
            'phone',
            'mobile',
            // 'is_deleted',
            [
                'attribute' => 'creator_id',
                'value' => function($data) {
                    return $data->creator->username;
                }
            ],
            'created_at:date',
            // 'deletor_id',
            // 'deleted_at',
        ],
    ]) ?>

</div>
