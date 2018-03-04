<?php
namespace backend\models;
use yii\base\Model;

class Repassword extends Model{
    public $repwd;
    public function rules()
    {
        return [
            ['repwd','required']
        ];
    }
    public function attributeLabels()
    {
        return [
            'repwd'=>'重置密码为:'
        ];
    }
}