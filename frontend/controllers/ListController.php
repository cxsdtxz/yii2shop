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

        //查询商品信息,根据goods_category_id查询
//        $goodses = Goods::find()->where(['goods_category_id'=>$goods_category_id])->andWhere(['status'=>1])->limit($pager->limit)->offset($pager->offset)->all();


        $cate = GoodsCategory::findOne(['id'=>$goods_category_id]);
        //处理分类不存在的情况
        switch ($cate->depth){
            case 0://1级分类
            case 1://2级分类
                $ids = $cate->children()->select(['id'])->andWhere(['depth'=>2])->asArray()->column();
                break;
            case 2://3级分类
                $ids = [$goods_category_id];
                break;
        }

        //分页
        $total = Goods::find()->where(['in','goods_category_id',$ids])->andWhere(['status'=>1])->count();
        $pager = new Pagination();
        $pager->defaultPageSize = 2;
        $pager->totalCount = $total;
        //一级分类
        //2  => [3,6]   =>  [15,16,17]
        $goodses = Goods::find()->where(['in','goods_category_id',$ids])->andWhere(['status'=>1])->offset($pager->offset)->limit($pager->limit)->all();

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
