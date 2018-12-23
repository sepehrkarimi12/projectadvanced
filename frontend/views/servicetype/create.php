<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Servicetype */

$this->title = Yii::t('app', 'Create Servicetype');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Servicetypes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicetype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
