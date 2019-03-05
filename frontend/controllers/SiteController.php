<?php
namespace frontend\controllers;

use yii\data\ActiveDataProvider;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\Servicetype;
use frontend\models\ContactForm;
use common\components\Zmodel;

/**
 * Site controller
 */
class SiteController extends Controller implements \common\components\permissions
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'up', 'down'],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['up', 'down'],
                        'allow' => false,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        // $a = null;
        // echo empty($a = null); //1
        // echo empty($a = ''); //1
        // echo empty($a = []); //1
        // echo empty($a = "null"); //0
        // echo empty($a = 'null'); //0
        // die();
        // -----------
        // echo "<pre>";
        // print_r(Yii::$app->db->slave);
        // -----
        // $command = (new \yii\db\Query())
        // ->select(['id', 'title'])
        // ->from('servicetype')
        // ->where(['last_name' => 'ali'])
        // ->limit(10)
        // ->createCommand();

        // // show the SQL statement
        // echo $command->sql . "<br/>";
        // // show the parameters to be bound
        // print_r($command->params);
        // ----------
        // $query = Servicetype::find();

        // $provider = new ActiveDataProvider([
        //     'query' => $query,
        // ]);

        // // returns an array of Post objects
        // $Servicetypes = $provider->getModels();
        // print_r($Servicetypes);
        // // returns the primary key values corresponding to $posts
        // $ids = $provider->getKeys();
        // print_r($ids);
        // ---------
        // $record = Servicetype::findOne(5);
        // // above code is equal with down code
        // // $record = Servicetype::findOne(5)->toArray();
        // print_r($record->attributes);
        // ------
        // $record = Servicetype::findOne(7);
        // print_r( $record->services );
        // print_r( $record->getServices() );
        // die;

        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    // public function actionSignup()
    // {
    //     $model = new SignupForm();
    //     if ($model->load(Yii::$app->request->post())) {
    //         if ($user = $model->signup()) {
    //             if (Yii::$app->getUser()->login($user)) {
    //                 return $this->goHome();
    //             }
    //         }
    //     }

    //     return $this->render('signup', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionUp()
    {
        $this->upPermissions();
    }

    public function actionDown()
    {
        $this->downPermissions();
    }

    /**
     * @auth
     *
     */
    public function upPermissions()
    {
        Zmodel::runPermissions();
    }

    public function downPermissions()
    {
        Zmodel::deletePermissions();
    }

    public function __clone()
    {
       echo 'You have created a clone of <'.Yii::$app->controller->action->id. '> action <'. Yii::$app->controller->id. '> controller ' . '<br>';
       die();
    }

    public function actionClone()
    {
        $clone = clone $this;
    }

    public function actionCall()
    {
        $this->thisIsMethodName('arguman1','arguman2');
    }

    public function __call( $name, $params )
    {
       echo $name . ' method does not exist';
       print_r($params);
       echo '<br>';
       die();
    }

}
