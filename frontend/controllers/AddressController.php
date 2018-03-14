<?php

namespace frontend\controllers;

use frontend\models\Address;

class AddressController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //查询该用户的地址信息
        $addresses = Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        return $this->render('address',['addresses'=>$addresses]);
    }

    public function actionAdd(){
        //实例化模型
        $model = new Address();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                //判断是否勾选默认,勾选了赋值为1 没有就为0
//                var_dump($model->def);die();
                if($model->def != null){
                    $model->default = 1;
                }else{
                    $model->default = 0;
                }
                //绑定用户id
                $model->member_id = \Yii::$app->user->id;
                //保存
                $model->save();
                return $this->redirect(['address/home']);
            }else{
                var_dump($model->getErrors());die();
            }
        }
//        return $this->render('address');
    }
    //ajax请求,获取数据
    public function actionGetAddress($id){
        //找到对应的模型
        $model = Address::findOne(['id'=>$id]);
        if($model){
            return json_encode([
                'name'=>$model->name,
                'province'=>$model->province,
                'city'=>$model->city,
                'county'=>$model->county,
                'address'=>$model->address,
                'tel'=>$model->tel,
                'def'=>$model->default,
                'address_id'=>$model->id
            ]);
        }
    }

    public function actionEdit(){
        $request = \Yii::$app->request;
        $id = $request->post('address_id');
        $model = Address::findOne(['id'=>$id]);
        $model->scenario = Address::SCENARIO_EDIT;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                //判断是否勾选默认,勾选了赋值为1 没有就为0
                if($model->def != null){
                    $model->default = 1;
                }else{
                    $model->default = 0;
                }
                $model->save();
                return $this->redirect(['address/home']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return json_encode([
            'res' => 1
        ]);
    }

    public function actionDelete($id){
        //实例化对象
        $model = Address::findOne(['id'=>$id]);
        if($model->delete()){
            return json_encode([
                'res'=>1
            ]);
        }else{
            return json_encode([
                'res'=>0
            ]);
        }
    }

    //设置地址为默认地址
    public function actionDefault($id){
        $model = Address::findOne(['id'=>$id]);
        if ($model->default == 1){
            $model->default = 0;
        }else{
            $model->default = 1;
        }
        $model->save();
        return $this->redirect(['address/home']);
    }
}
