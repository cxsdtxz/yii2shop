<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Cart;
use yii\web\Cookie;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex($id)
    {
        //查询第一层数据
        $ones = GoodsCategory::find()->where(['parent_id'=>0])->all();
        //根据id查到对应的商品信息
        $goods = Goods::findOne(['id'=>$id]);
        //查出对应的相册的图片
        $pics = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        //商品详情
        $intro = GoodsIntro::findOne(['goods_id'=>$id]);
        //访问页面点击量加一
        $goods->view_times = $goods->view_times + 1;
        $goods->save();
        return $this->render('index',['goods'=>$goods,'pics'=>$pics,'intro'=>$intro,'ones'=>$ones]);
    }


    //添加商品到购物车
    public function actionAddCarts($goods_id,$amount){
        //根据收到的goods_id 和 商品数量 判断用户是否登录,登录保存到数据库,为登录保存到cookie
        if(\Yii::$app->user->isGuest){
            //保存到cookie前先判断购物车中是否存在商品的信息,避免覆盖
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }

            //判断goods_is是否存在,如果存在就累加商品数量,不能覆盖
            if (array_key_exists($goods_id,$carts)){
                $carts[$goods_id] += $amount;
            }else{
                $carts[$goods_id] = $amount;
            }
            //保存cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookie->expire = time()+7*24*60*60;
            $cookies->add($cookie);

        }else{
            //保存到数据库
            $model = Cart::findOne(['goods_id'=>$goods_id]);
            if ($model){
                //保存原来的数量
                $num = $model->amount;
                //如果数据库中有这个商品的信息,则修改商品的数量
                $request = \Yii::$app->request;
                if($request->isGet){
                    $model->load($request->get(),'');
                    if($model->validate()){
                        //累计数量
                        $model->amount = $model->amount + $num;
                        $model->save();
                    }
                }
            }else{
                //没有这个商品,所有新添加一条
                $request = \Yii::$app->request;
                $model = new Cart();
                if($request->isGet){
                    $model->goods_id = $goods_id;
                    $model->amount = $amount;
                    if($model->validate()){
                        //保存用户id
                        $model->member_id = \Yii::$app->user->id;
                        $model->save();
                    }else{
                        var_dump($model->getErrors());
                    }
                }
            }
        }
        //加载视图
        return $this->render('success');
    }

    //购物车
    public function actionCarts(){
        //判断是否是游客
        if(\Yii::$app->user->isGuest){
            //获取cookie里的信息
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if ($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
        }else{
            //获取数据库里的信息
            $carts = [];
            $models = Cart::find()->all();
            foreach ($models as $model){
                $carts[$model->goods_id] = $model->amount;
            }
        }

        return $this->render('carts',['carts'=>$carts]);
    }

    //ajax操作购物车
    public function actionAjaxCarts($goods_id,$amount){
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }

            //amount为0 时表示删除,其他时候表示修改
            if($amount){
                //修改cookie
                $carts[$goods_id] = $amount;
                //保存到cookie
                $cookies = \Yii::$app->response->cookies;
                $cookie = new Cookie();
                $cookie->name = 'carts';
                $cookie->value = serialize($carts);
                $cookie->expire = time()+7*24*60*60;
                $cookies->add($cookie);
            }else{
                //表示删除
                unset($carts[$goods_id]);
                //保存到cookie
                $cookies = \Yii::$app->response->cookies;
                $cookie = new Cookie();
                $cookie->name = 'carts';
                $cookie->value = serialize($carts);
                $cookie->expire = time()+7*24*60*60;
                $cookies->add($cookie);
                return json_encode("success");
            }
        }else{
            //修改数据库
            $cart = Cart::findOne(['goods_id'=>$goods_id]);
            if($amount){
                //表示修改
                $cart->amount = $amount;
                $cart->save();
            }else{
                //表示删除
                $cart->delete();
                return json_encode("success");
            }
        }
    }
}
