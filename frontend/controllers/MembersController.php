<?php

namespace frontend\controllers;

use frontend\aliyun\SignatureHelper;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Members;
use function Sodium\crypto_box_keypair_from_secretkey_and_publickey;

class MembersController extends \yii\web\Controller
{
    //登录
    public function actionLogin(){
        //接受数据
        $request = \Yii::$app->request;
        $model = new LoginForm();
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                //判断是否登录成功
                if($model->login()){
                    //登录成功保存最后登录时间和ip
                    $member = Members::findOne(['username'=>$model->username]);
                    $member->last_login_time = time();
                    $member->last_login_ip = ip2long($request->getUserIP());
                    $member->save(0);

                    //同步购物车的数据到数据库
                    //从cookie里面取出数据
                    $cookies = \Yii::$app->request->cookies;
                    $value = $cookies->getValue('carts');
                    if($value){
                        //将数据反序列化
                        $carts = unserialize($value);
                        //将数据保存到数据库
                        foreach ($carts as $goods_id=>$amount){
                            $cart = Cart::findOne(['goods_id'=>$goods_id]);
                            if($cart){//判断该商品在数据库中是否存在,如果存在就累加数量
                                $cart->amount = $cart->amount + $amount;
                                $cart->save();
                            }else{//商品在数据库中不存在,就添加一条信息
                                $cart = new Cart();
                                $cart->goods_id = $goods_id;
                                $cart->amount = $amount;
                                $cart->member_id = \Yii::$app->user->id;
                                $cart->save();
                            }
                        }
                        //保存完成后清除cookie
                        $cookies = \Yii::$app->response->cookies;
                        $cookies->remove('carts');
                    }
                    //跳转页面
                    return $this->redirect(['home/index']);
                }
            }
        }
        return $this->render('login');
    }

    //注销登录
    public function actionLogout()
    {
        //使用user组件方法注销登录
        \Yii::$app->user->logout();
        //跳转页面
        \Yii::$app->session->setFlash('success', '注销成功');
        return $this->redirect(['home/index']);
    }



    public function actionIndex()
    {
        var_dump(\Yii::$app->user->isGuest);

        return $this->render('index');
    }


    public function actionRegister(){
        //实例化模型
        $model = new Members();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            //给密码加密
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
            if($model->validate()){
                //生成auth_key
                $model->auth_key = \Yii::$app->security->generateRandomString();
                //绑定添加时间
                $model->created_at = time();
                $model->status = 1;
                //保存数据
                $model->save(0);
                \Yii::$app->session->setFlash('success','注册成功');
                return $this->redirect(['members/home']);
            }
        }
        return $this->render('register');
    }


    //验证用户名是否重复
    public function actionCheckUsername($username){
        //根据接受到的用户名查,判断查询结果是否存在
        $model = Members::findOne(['username'=>$username]);
        if($model){
            return "false";
        }else{
            return "true";
        }
    }


    //发送短信
    public function actionSms($tel){
        //保存code到redis
        $code = rand(100000,999999);
        //开启redis
        $redis = new \Redis();
        $redis->connect("127.0.0.1");
        $redis->set('code_'.$tel,$code,300);
        //发送
        $r = \Yii::$app->sms->setTel($tel)->setParams(['code'=>$code])->send();
        if($r){
            return 'success';
        }else{
            return 'fail';
        }
    }

    //验证手机验证码
    public function actionValidateSms($tel,$code){
        //取出redis中的验证码
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $verify = $redis->get('code_'.$tel);
        if ($verify == $code){
            return 'true';
        }else{
            return 'false';
        }
    }
}
