<?php
namespace backend\models;
use yii\base\Model;

class PermissionForm extends Model{
    public $permission;
    public $description;
    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';

    public function rules()
    {
        return [
            [['permission','description'],'required'],
            ['permission','validateName','on'=>self::SCENARIO_ADD],
            ['permission','editName','on'=>self::SCENARIO_EDIT],
        ];
    }

    public function validateName(){
        //实例化authManager
        $authManager = \Yii::$app->authManager;
        if($authManager->getPermission($this->permission)){
            //添加错误信息
            return $this->addError('permission','该权限已存在');
        }
    }

    public function editName(){
        //修改name的时候需要验证,从request里获取老的name
        $request = \Yii::$app->request;
        if($this->permission != $request->get('name')){
            //验证权限是否已经存在
            $this->validateName();
        }
    }


    public function attributeLabels()
    {
        return [
            'permission'=>'权限(路由)',
            'description'=>'描述'
        ];
    }
}