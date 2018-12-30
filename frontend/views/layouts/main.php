<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        // ['label' => 'About', 'url' => ['/site/about']],
        // ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        // $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }

    if(Yii::$app->user->can('admin user'))
        $menuItems[] = ['label' => 'user', 'url' => ['/user/index']];
    if(Yii::$app->user->can('admin role'))
        $menuItems[] = ['label' => 'role', 'url' => ['/role/index']];
    if(Yii::$app->user->can('admin customer'))
        $menuItems[] = ['label' => 'customer', 'url' => ['/customer/index']];
    if(Yii::$app->user->can('admin comment'))
        $menuItems[] = ['label' => 'comment', 'url' => ['/comment/index']];

    if(Yii::$app->user->can('admin service'))
        $menuItems[] = ['label' => 'service', 'url' => ['/service/index']];
    if(Yii::$app->user->can('admin servicetype'))
        $menuItems[] = ['label' => 'servicetype', 'url' => ['/servicetype/index']];
    
    if(Yii::$app->user->can('admin network'))
        $menuItems[] = ['label' => 'Network', 'url' => ['/network/index']];
    if(Yii::$app->user->can('admin networktype'))
        $menuItems[] = ['label' => 'networktype', 'url' => ['/networktype/index']];
    
    if(Yii::$app->user->can('admin radio'))
        $menuItems[] = ['label' => 'radio', 'url' => ['/radio/index']];
    

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
