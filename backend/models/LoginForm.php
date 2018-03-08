<?php

namespace backend\models;

use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $auto_login;
    public $verify;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['auto_login', "safe"],
            ['verify', 'captcha', 'captchaAction' => 'admin/captcha']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'verify' => '验证码:',
            'auto_login' => '记住我!'
        ];
    }

    public function login()
    {
        //根据用户名查询出密码,在和传过来的密码比较
        $admin = Admin::findOne(['username' => $this->username]);
        //用户是否存在    和  用户名存在比较密码是否一致
        if ($admin && \Yii::$app->security->validatePassword($this->password, $admin->password_hash)) {
            //密码一致,登录成功 保存用户信息到session
            return \Yii::$app->user->login($admin, $this->auto_login ? 3600 * 7 * 24 : 0);
        }
        return false;
    }
}