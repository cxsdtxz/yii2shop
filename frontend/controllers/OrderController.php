<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/14
 * Time: 11:18
 */

namespace frontend\controllers;


use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Delivery;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Payment;
use yii\db\Exception;
use yii\web\Controller;

class OrderController extends Controller
{
    public function actionSettlement(){
        //判断用户是否登录,如果没登录跳回登录页面
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['members/login']);
        }
        //查询用户的收货地址
        $member_id = \Yii::$app->user->id;
        $addresses = Address::find()->where(['member_id'=>$member_id])->all();

        //根据member_id,查询cart表里的数据
        $carts = Cart::find()->where(['member_id'=>$member_id])->all();

        //查询送货方式和支付方式
        $payments = Payment::find()->all();
        $deliverys = Delivery::find()->all();

        //加载页面
        return $this->render('settlement',['addresses'=>$addresses,'carts'=>$carts,'payments'=>$payments,'deliverys'=>$deliverys]);
    }


    public function actionSaveOrder(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $address_id = $request->post('address_id');
            $payment_id = $request->post('pay');
            $delivery_id = $request->post('delivery');
            //分别回去对应数据,添加到order表
            $order = new Order();
            //查询地址信息
            $address = Address::findOne(['id'=>$address_id]);
            //查询支付信息
            $payment = Payment::findOne(['payment_id'=>$payment_id]);
            //查询送货方式信息
            $delivery = Delivery::findOne(['delivery_id'=>$delivery_id]);

            //加载数据
            $order->member_id = \Yii::$app->user->id;
            $order->name = $address->name;
            $order->province = $address->province;
            $order->city = $address->city;
            $order->county = $address->county;
            $order->address = $address->address;
            $order->tel = $address->tel;
            $order->delivery_id = $delivery_id;
            $order->delivery_name = $delivery->delivery_name;
            $order->delivery_price = $delivery->delivery_price;
            $order->payment_id = $payment_id;
            $order->payment_name = $payment->payment_name;
            $order->total = 0;
            $order->status = 1;
            $order->create_time = time();

            //开启事务
            $transaction = \Yii::$app->db->beginTransaction();
            try{
                $order->save();
                //添加订单商品详情表
                $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
                if(!$carts){
                    throw new Exception('购物车没有商品');

                }
                foreach ($carts as $cart){
                    $goods = Goods::findOne(['id'=>$cart->goods_id]);
                    //检查库存是否够
                    if($goods->stock < $cart->amount){
                        throw new Exception("商品库存不足");
                    }
                    //减少库存
                    $goods->stock -= $cart->amount;
                    $goods->save();

                    $orderGoods = new OrderGoods();
                    $orderGoods->order_id = $order->id;
                    $orderGoods->goods_id = $goods->id;
                    $orderGoods->goods_name = $goods->name;
                    $orderGoods->logo = $goods->logo;
                    $orderGoods->price = $goods->shop_price;
                    $orderGoods->amount = $cart->amount;
                    $orderGoods->total = $orderGoods->amount * $orderGoods->price;
                    //计算总金额
                    $order->total += $orderGoods->total;
                    $orderGoods->save();
                }
                $order->total += $order->delivery_price;
                $order->save();

                //清除购物车
                Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);

                $transaction->commit();

            }catch (Exception $e){
                $transaction->rollBack();

                return $this->redirect(['goods/carts']);
            }

        }


        return $this->render('success');
    }

    public function actionIndex(){

        //查询order表数据
        $orders = Order::find()->where(['member_id'=>\Yii::$app->user->id])->all();

        return $this->render('index',['orders'=>$orders]);
    }

    public function actionDel($id){
        $order = Order::findOne(['id'=>$id]);
        $order->status = 0;
        if($order->save()){
            return json_encode('success');
        }else{
            return json_encode("fail");
        }

    }

}