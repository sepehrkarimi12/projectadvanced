<?php

namespace app\modules\lte;

use Yii;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\lte\controllers';
    public $defaultRoute = 'lte';
    public function init()
    {
        parent::init();
    }
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\lte\commands';
        }
    }

}