<?php
namespace app\models;
use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;
class SmsForm extends Model
{
    public $sms_code;
    public $login;
    public $rememberMe;
    private $_code = false;
    private $_user = false;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['sms_code', 'required', 'message' => 'Введите код!'],
            ['login', 'required'],
            ['sms_code', 'string'],
            [['sms_code'], 'validateEmpty', 'skipOnEmpty'=> false],
            ['sms_code', 'validateVerifycode'],
            ['rememberMe', 'boolean'],
        ];
    }
    public function validateEmpty()
    {
        if(empty($this->sms_code))
        {
            $errorMsg= 'Введите код!';
            $this->addError('sms_code',$errorMsg);
        }
    }
    public function validateVerifycode($attribute, $params)
    {
        if (!$this->hasErrors()){
            $code = $this->getCode();
            if (!password_verify($this->sms_code,$code)) {
                $this->addError($attribute, 'Не совпадают!');
            }
        }
    }
    public function checking()
    {
        if ($this->validate()){
            $timeOut = Users::find()->where(['login'=>$this->login])->one()->time_out;
            if(empty($timeOut)||$timeOut<3600){
                $timeOut = 3600;
            }
            /*System::mesprint($this->rememberMe);
            System::mesprint($timeOut);
            die();*/
            return Yii::$app->user->login($this->getUser(), ($this->rememberMe?$timeOut:0));//$user
        }
        return false;
    }
    protected function getCode()
    {
        if (empty($this->_code)) {
            $this->_code =  User::find()->where(['login'=>$this->login,])->one()->enter_key;
        }
        return $this->_code;
    }
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->login);
        }
        return $this->_user;
    }
}