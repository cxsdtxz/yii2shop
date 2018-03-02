<?php

namespace backend\controllers;

use backend\models\Goods;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAdd(){
        //实例化模型
        $model = new Goods();

        //加载视图
        return $this->render('add',['model'=>$model]);
    }

}
