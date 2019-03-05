<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Role */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="role-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(
            Yii::t('app', 'Update'),
            ['update', 'id' => $model->name],
            ['class' => 'btn btn-primary']) 
        ?>
        <?= 
        Html::a(
            Yii::t('app', 'Delete'),
            [
                'delete',
                'id' => $model->name
            ],
            [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) 
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            // 'type',
            'description:ntext',
            // 'rule_name',
            // 'data',
            'created_at:date',
            // 'updated_at',
        ],
    ]) ?>

    <h3>Permisiions :</h3>
    <?php
    foreach ($permissions as $key => $value) {
       echo $value.' - ';
    } 
    ?>

</div>
