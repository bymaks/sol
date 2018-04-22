<?php
namespace app\controllers;
use app\models\SmsForm;
use app\models\SmsFrom;
use app\models\System;
use app\models\User;
use app\models\LoginForm;
use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
class SiteController extends Controller
{
    public $defaultAction = 'login';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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
    // CSRF;
    public function beforeAction($action)
    {
        //  $this->enableCsrfValidation = false;
        if (in_array($action->id, ['test'])) {
            // $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
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
    //Displays homepage
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest){
            $smsKey = Users::find()->where(['id'=>Yii::$app->user->id])->one();
            if(!empty($smsKey)){
                if(empty($smsKey->enter_key)){
                    Yii::$app->user->logout();
                    return $this->goHome();
                }
            }
            return $this->render('index');
        }
        else
            return $this->redirect('login');
    }
    //Login action
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $smsKey = Users::find()->where(['id'=>Yii::$app->user->id])->one();
            if(!empty($smsKey)){
                if(empty($smsKey->enter_key)){
                    Yii::$app->user->logout();
                    return $this->goHome();
                }
            }
            return $this->render('index');
        }
        $model = new LoginForm();
        if(!empty(Yii::$app->request->post('SmsForm'))){
            $modelSms = new SmsForm();
            if($modelSms->load(Yii::$app->request->post()) && $user = $modelSms->checking()){
                return $this->redirect('/site/index');
            }
            else
            {
                return $this->render('smscheck',[
                    'model' => $modelSms,
                ]);
            }
        }
        if ($model->load(Yii::$app->request->post())){
            $user = User::findByUsername($model->username);
            if(!empty($user)) {// чувак есть
                if ($userFind = $model->login()) {// все четко
                    //сгенерить смс отправить смс зашифровать и в базу записать
                    $smsKey = 1;//rand(10000, 99999);
                    $userFind->enter_key = password_hash($smsKey, PASSWORD_BCRYPT);
                    if($userFind->save(true)){
                        System::sendTelegrammPerconal('Key: '.$smsKey."");
                        unset($smsKey);
                        $modelSms = new SmsForm();
                        $modelSms->login = $user->login;
                        $modelSms->rememberMe = $model->rememberMe;
                        return $this->render('smscheck',[
                            'model' => $modelSms,
                        ]);
                    }
                    else{
                        var_dump($userFind->errors);
                        die();
                    }
                }
            }
        }
        return $this->render(
            'login', [
                'model' => $model,
            ]
        );
    }
    //Logout action.
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}