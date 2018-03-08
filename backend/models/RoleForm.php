<?php
namespace backend\models;
use yii\base\Model;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permission;
    const SCENARIO_ADD= 'add';
    const SCENARIO_EDIT = 'edit';

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permission','safe'],
            ['name','validateName','on'=>self::SCENARIO_ADD],
            ['name','editName','on'=>self::SCENARIO_EDIT]
        ];
    }
    //自定义验证规则
    public function validateName(){
        //验证角色是否已经存在
        $authManager = \Yii::$app->authManager;
        if ($authManager->getRole($this->name)){
            //添加错误信息
            return $this->addError('name','该角色已经存在');
        }
    }

    //修改的验证规则
    public function editName(){
        //验证是否修改name 如果没有修改不需要验证
        $request = \Yii::$app->request;
        if($request->get('name') != $this->name){
            $this->validateName();
        }
    }


    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'description'=>'描述',
            'permission'=>'权限'
        ];
    }

    //获取所有权限
    public function getPermissions(){
        //查询所有权限
        $authManager = \Yii::$app->authManager;
        $permissions = $authManager->getPermissions();
        $arr = [];
        foreach ($permissions as $permission){
            $arr[$permission->name] = $permission->description;
        }
        return $arr;
    }

    //获取角色拥有的权限
    public function getRolePermission(){
        //查询所有权限
        $authManager = \Yii::$app->authManager;
        $request = \Yii::$app->request;
        $permissions = $authManager->getPermissionsByRole($request->get('name'));
        $arr = [];
        foreach ($permissions as $permission){
            $arr[] = $permission->name;
        }
        return $arr;
    }
}