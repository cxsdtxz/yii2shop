<?php
namespace frontend\models;
use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password;
    public $auth_key;
    public $captcha;

    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['auth_key','safe'],
            ['captcha','captcha','captchaAction' => 'site/captcha']
        ];
    }

    //验证用户名和密码是否正确
    public function Login(){
        //根据用户名查数据,如果对象存在,再比较密码是否一致
        $member = Members::findOne(['username'=>$this->username]);
        if($member){
            //比较密码是否一致
            if(\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
                //登录成功,保存session
                return \Yii::$app->user->login($member,$this->auth_key ? 3600*7*24 : 0);
            }
        }
        return false;
    }
}