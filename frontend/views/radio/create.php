<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Radio */

$this->title = Yii::t('app', 'Create Radio');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Radios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="radio-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
