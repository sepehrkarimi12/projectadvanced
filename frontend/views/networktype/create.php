<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Networktype */

$this->title = Yii::t('app', 'Create Networktype');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Networktypes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="networktype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
