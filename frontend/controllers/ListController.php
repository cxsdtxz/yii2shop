<?php

namespace frontend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\data\Pagination;

class ListController extends \yii\web\Controller
{
    public function actionIndex($goods_category_id)
    {
        //查询第一层数据
        $ones = GoodsCategory::find()->where(['parent_id'=>0])->all();
        //查询所有品牌信息
        $brands = Brand::find()->all();

        //分页
        $total = Goods::find()->where(['goods_category_id'=>$goods_category_id])->andWhere(['status'=>1])->count();
        $pager = new Pagination();
        $pager->defaultPageSize = 2;
        $pager->totalCount = $total;

        //查询商品信息,根据goods_category_id查询
        $goodses = Goods::find()->where(['goods_category_id'=>$goods_category_id])->andWhere(['status'=>1])->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['brands'=>$brands,'goodses'=>$goodses,'ones'=>$ones,'pager'=>$pager]);
    }

    //增加点击数
    public function actionViewTimes($goods_id){
        $goods = Goods::findOne(['id'=>$goods_id]);
        $goods->view_times = $goods->view_times + 1;
        if($goods->save()){
            return "success";
        }else{
            return "fail";
        }
    }
}
