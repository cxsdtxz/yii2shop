<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Admin;
use backend\models\EditPassword;
use backend\models\LoginForm;
use backend\models\Repassword;
use yii\filters\AccessControl;
use yii\web\HttpException;

class AdminController extends \yii\web\Controller
{
    //登录
    public function actionLogin()
    {
        //1 登录表单
        $model = new LoginForm();
        //2 接受数据
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //3 验证用户信息
                if ($model->login()) {
                    //登录成功,保存登录时间和登录ip
                    $admin = Admin::findOne(['id' => \Yii::$app->user->id]);
                    $admin->last_login_time = time();
                    $admin->last_login_ip = \Yii::$app->getRequest()->getUserIP();
                    $admin->save(0);
                    //4 跳转
                    \Yii::$app->session->setFlash('success', '登录成功');
                    return $this->redirect(['site/home']);
                }else{
                    \Yii::$app->session->setFlash('danger', '登录失败');
                }
            }
        }
        return $this->render('login', ['model' => $model]);
    }

    //注销登录
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success', '注销成功');
        return $this->redirect(['admin/login']);
    }



    //验证码
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }


    public function actionIndex()
    {
        //查询所有数据
        $admins = Admin::find()->all();

        return $this->render('index', ['admins' => $admins]);
    }

    public function actionAdd()
    {
        //创建模型
        $model = new Admin();
        //使用场景
        $model->scenario = Admin::SCENARIO_ADD;
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            //验证
            if ($model->validate()) {
                //绑定创建时间,更新时间等
                $model->created_at = time();
                $model->updated_at = time();
                $model->last_login_time = 0;
                $model->last_login_ip = 0;
                //设置autoKey
                $model->auth_key = \Yii::$app->security->generateRandomString();
                //给密码加密
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save(0);
                //保存成功后添加关联角色
                if(is_array($model->roles)){
                    $authManager = \Yii::$app->authManager;
                    foreach ($model->roles as $name){
                        $role = $authManager->getRole($name);
                        $authManager->assign($role,$model->id);
                    }
                }
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['admin/index']);
            }
        }
        //加载视图
        return $this->render('add', ['model' => $model]);
    }

    //修改基本信息
    public function actionEdit($id)
    {
        //创建模型
        $model = Admin::findOne(['id' => $id]);
        if (!$model){
            throw new HttpException('404','该用户不存在或已删除');
        }
        //使用场景
        $model->scenario = Admin::SCENARIO_EDIT;
        //回显多选框
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($id);
        $item = [];
        foreach ($roles as $role){
            $item[] = $role->name;
        }
        $model->roles = $item;
        //判断传输方式
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            //验证
            if ($model->validate()) {
                //绑定更新时间
                $model->updated_at = time();
                $model->save(0);
                //保存完基本信息,删除该用户关联的所有角色,重新添加新的角色
                $authManager->revokeAll($id);
                if(is_array($model->roles)){
                    foreach ($model->roles as $name) {
                        $role = $authManager->getRole($name);
                        $authManager->assign($role,$id);
                    }
                }
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['admin/index']);
            } else {
                var_dump($model->getErrors());exit();
            }
        }
        //加载视图
        return $this->render('add', ['model' => $model]);
    }

    //修改自己的密码
    public function actionEditPassword()
    {
        $model = new EditPassword();
        //判断传输方式
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            //验证是否合法
            if ($model->validate()) {
                //验证旧密码是否正确
                $admin = Admin::findOne(['id' => \Yii::$app->user->id]);
                if (\Yii::$app->security->validatePassword($model->old_password, $admin->password_hash)) {
                    $admin->password_hash = \Yii::$app->security->generatePasswordHash($model->new_password);
                    $admin->save(0);
                    \Yii::$app->user->logout();
                    \Yii::$app->session->setFlash('success', '修改成功,请重新登录!');
                    return $this->redirect(['admin/login']);
                } else {
                    $model->addError('old_password', '旧密码不正确');
                }
            }
        }
        return $this->render('edit', ['model' => $model]);
    }

    //重置密码
    public function actionRePassword($id){
        //获取模型
        $model = new Repassword;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //保存数据到admin表
                $admin = Admin::findOne(['id'=>$id]);
                //对密码加密
                $admin->password_hash = \Yii::$app->security->generatePasswordHash($model->repwd);
                $admin->save(0);
                \Yii::$app->session->setFlash('success','重置成功');
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('repassword',['model'=>$model]);
    }

    public function actionDelete($id)
    {
        //查询对应模型
        $model = Admin::findOne(['id' => $id]);
        if (!$model){
            throw new HttpException('404','该用户不存在或已删除');
        }
        $model->delete();
        //删除该用户关联的所有角色关系
        $authManager = \Yii::$app->authManager;
        $authManager->revokeAll($id);
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['admin/index']);
    }


    //rbac的过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
                'except'=>['login','logout','captcha','edit-password']
            ]
        ];
    }

}
