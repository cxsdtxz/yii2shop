<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\EditPassword;
use backend\models\LoginForm;
use backend\models\Repassword;
use yii\filters\AccessControl;

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
                    return $this->redirect(['admin/index']);
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

    //配置过滤器  添加访问权限
    public function behaviors()
    {
        return [
            'acf' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'add', 'edit', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'add', 'edit', 'delete'],
                        'roles' => ['@'],
                    ],
                ]
            ]
        ];
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
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['admin/index']);
            } else {
                var_dump($model->getErrors());
                die();
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
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            //验证
            if ($model->validate()) {
                //绑定更新时间
                $model->updated_at = time();
                //给密码加密
                $model->save(0);
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['admin/index']);
            } else {
                var_dump($model->getErrors());
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
                    \Yii::$app->session->setFlash('success', '修改成功');
                    return $this->redirect(['admin/index']);
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
        $model->delete();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['admin/index']);
    }

}
