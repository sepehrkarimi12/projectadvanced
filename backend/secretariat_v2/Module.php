<?php

namespace app\modules\secretariat_v2;


use yii\base\BootstrapInterface;

class Module extends \Yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\secretariat_v2\controllers';

    public function init()
    {
        parent::init();
    }

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\secretariat_v2\commands';
        }
    }
}