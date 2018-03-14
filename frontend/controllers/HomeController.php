<?php

namespace frontend\controllers;

use backend\models\GoodsCategory;

class HomeController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //查询第一层数据
        $ones = GoodsCategory::find()->where(['parent_id'=>0])->all();

        return $this->render('index',['ones'=>$ones]);
    }

}
