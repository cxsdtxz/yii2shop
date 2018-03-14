<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\HttpException;

class RbacController extends \yii\web\Controller
{
    /**
     * @return string
     * 权限列表
     */
    public function actionIndexPermission()
    {
        //查询所有权限
        $authManager = \Yii::$app->authManager;
        $permissions = $authManager->getPermissions();
        return $this->render('indexPermission', ['permissions' => $permissions]);
    }

    /**
     * @return string|\yii\web\Response
     * 添加权限
     */
    public function actionAddPermission()
    {
        //实例化表单模型
        $model = new PermissionForm();
        //使用场景
        $model->scenario = PermissionForm::SCENARIO_ADD;
        //接受验证数据
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //添加权限
                $authManager = \Yii::$app->authManager;
                $permission = $authManager->createPermission($model->permission);
                $permission->description = $model->description;
                if ($authManager->add($permission)) {
                    \Yii::$app->session->setFlash("success", "添加成功");
                    return $this->redirect(['rbac/index-permission']);
                }
            }
        }
        //加载视图
        return $this->render('addPermission', ['model' => $model]);
    }

    /**
     * @param $name
     * @return string|\yii\web\Response
     * 修改权限
     */
    public function actionEditPermission($name)
    {
        //修改权限 查询对应的数据
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        if(!$permission){
            throw new HttpException('404','该权限不存在');
        }
        //实例化模型
        $model = new PermissionForm();
        //使用场景
        $model->scenario = PermissionForm::SCENARIO_EDIT;
        //回显  给Model赋值
        $model->permission = $permission->name;
        $model->description = $permission->description;
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //给
                $permission->name = $model->permission;
                $permission->description = $model->description;
                if ($authManager->update($name, $permission)) {
                    //修改成功
                    \Yii::$app->session->setFlash('success', '修改成功');
                    return $this->redirect(['rbac/index-permission']);
                }
            }
        }
        //加载视图
        return $this->render('addPermission', ['model' => $model]);
    }


    /**
     * @param $name
     * @return \yii\web\Response
     * 删除权限
     */
    public function actionDeletePermission()
    {
        $requset = \Yii::$app->request;
        $name = $requset->get('name');
        //通过name 找到对应对象 删除
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        if(!$permission){
            throw new HttpException('404','该权限不存在');
        }
        if ($authManager->remove($permission)) {
           return json_encode([
               'res'=>1
           ]);
        } else {
            return json_encode([
                'res'=>0
            ]);
        }
    }

    //角色列表
    public function actionIndexRole(){
        //查询所有角色数据
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRoles();
        return $this->render('indexRole',['roles'=>$roles]);
    }

    /**
     * @return string|\yii\web\Response
     * 添加角色
     */
    public function actionAddRole(){
        //实例化表单模型
        $model = new RoleForm();
        //使用场景
        $model->scenario = RoleForm::SCENARIO_ADD;
        //接收数据
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //添加角色
                $authManager = \Yii::$app->authManager;
                $role = $authManager->createRole($model->name);
                $role->description = $model->description;
                if($authManager->add($role)){
                    //保存成功了给这个角色关联权限
                    $permissions = $model->permission;
                    if(is_array($permissions)){
                        foreach($permissions as $permission){
                            $per = $authManager->getPermission($permission);
                            $authManager->addChild($role,$per);
                        }
                    }
                    //添加完成,提示信息跳转
                    \Yii::$app->session->setFlash('success','添加成功');
                    return $this->redirect(['rbac/index-role']);
                }
            }
        }
        //加载视图
        return $this->render('addRole',['model'=>$model]);
    }

    /**
     * @param $name
     * @return string|\yii\web\Response
     * 修改角色
     */
    public function actionEditRole($name){
        //实例化模型
        $model = new RoleForm();
        //使用场景
        $model->scenario = RoleForm::SCENARIO_EDIT;
        $request = \Yii::$app->request;
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        if(!$role){
            throw new HttpException('404','该角色不存在');
        }
        //回显数据
        $permissions = $model->getRolePermission();
        $model->permission = $permissions;
        $model->name = $role->name;
        $model->description = $role->description;
        //判断传输方式
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //修改角色信息
                $role->name = $model->name;
                $role->description = $model->description;
                if($authManager->update($name,$role)){
                    //删除该对象的所有权限关系
                    $authManager->removeChildren($role);
                    //添加角色和权限的关联
                    $permissions = $model->permission;
                    if(is_array($permissions)){
                        foreach($permissions as $permission){
                            $per1 = $authManager->getPermission($permission);
                            $authManager->addChild($role,$per1);
                        }
                    }
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['rbac/index-role']);
                }
            }
        }
        //加载视图
        return $this->render('addRole',['model'=>$model]);
    }

    //删除角色
    public function actionDeleteRole(){
        $requset = \Yii::$app->request;
        $name = $requset->get('name');
        //获取该角色对象
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        if(!$role){
            throw new HttpException('404','该角色不存在');
        }
        //删除该对象的所有权限关系
        $permissions = $authManager->getPermissionsByRole($name);
        foreach ($permissions as $permission){
            $authManager->removeChild($role,$permission);
        }
        //删除角色
        if ($authManager->remove($role)) {
            return json_encode([
                'res'=>1
            ]);
        } else {
            return json_encode([
                'res'=>0
            ]);
        }
    }


    //rbac的过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class
            ]
        ];
    }
}
