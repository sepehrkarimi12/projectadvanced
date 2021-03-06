<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Radio */

$this->title = Yii::t('app', 'Update Radio: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Radios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="radio-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'networks' => $networks,
    ]) ?>

</div>
