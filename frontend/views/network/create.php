<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Network */

$this->title = Yii::t('app', 'Create Network');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Networks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="network-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'networktypes' => $networktypes,
    ]) ?>

</div>
