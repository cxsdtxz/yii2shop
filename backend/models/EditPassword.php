<?php
namespace backend\models;
use yii\base\Model;

class EditPassword extends Model{
    public $old_password;
    public $new_password;
    public $re_password;
    public function rules()
    {
        return [
            [['old_password','new_password','re_password'],'required'],
            ['re_password', 'compare', 'compareAttribute' => 'new_password','message'=>'两次输入密码不一致!'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'old_password'=>'旧密码',
            'new_password'=>'新密码',
            're_password'=>'确认密码',
        ];
    }
}