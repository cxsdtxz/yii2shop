<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\GoodsSearch;
use yii\data\Pagination;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        //搜索
        $search = new GoodsSearch();
        $request = \Yii::$app->request;
        if($request->isGet){
            //接受搜索框传过来的值
            $name = $request->get('GoodsSearch')['name'];
            $sn = $request->get('GoodsSearch')['sn'];
            $price_min = $request->get('GoodsSearch')['price_min'];
            $price_max = $request->get('GoodsSearch')['price_max'];
            $search->name = $name;
            $search->sn = $sn;
            $search->price_min = $price_min;
            $search->price_max = $price_max;
            //判断价格区间,根据传过来的值 判断查询方式,组成不同的查询条件
            $sql = [];
            if($price_min !='' && $price_max == ''){
                $sql = ['<','shop_price',"{$price_min}"];
            }elseif ($price_min =='' && $price_max != ''){
                $sql = ['>','shop_price',"{$price_max}"];
            }elseif($price_min != '' && $price_max != ''){
                $sql = [
                    'and',
                    ['>','shop_price',"{$price_min}"],
                    ['<','shop_price',"{$price_max}"],
                ];
            }
        }

        //分页
        $total = Goods::find()->where(['status' => 1])->andWhere([
            'and',
            ['like','name',"{$name}"],
            ['like','sn',"{$sn}"],
        ])->andWhere($sql)->count();
        $pageSize = 5;
        $pager = new Pagination();
        $pager->totalCount = $total;
        $pager->defaultPageSize = $pageSize;
        //查询所有数据
        $goods = Goods::find()->where(['status' => 1])->offset($pager->offset)->limit($pager->limit)->andWhere([
            'and',
            ['like','name',"{$name}"],
            ['like','sn',"{$sn}"],

        ])->andWhere($sql)->all();
var_dump($name);
        return $this->render('index', ['goods' => $goods, 'pager' => $pager,'search'=>$search]);
    }


    //回收站
    public function actionRecycle(){
        //分页
        $total = Goods::find()->where(['status' => 0])->count();
        $pageSize = 5;
        $pager = new Pagination();
        $pager->totalCount = $total;
        $pager->defaultPageSize = $pageSize;
        //查询所有数据
        $goods = Goods::find()->where(['status' => 0])->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('recycle', ['goods' => $goods, 'pager' => $pager]);
    }


    public function actionAdd()
    {
        //实例化模型
        $model = new Goods();
        $content = new GoodsIntro();
        $request = \Yii::$app->request;
        //判断传输方式
        if ($request->isPost) {
            //生成sn 和 添加时间 状态默认为1
            $model->create_time = time();
            $model->status = 1;

            //获取goods_day_count 表中的日期,商品数,得到该次添加的时候为第几个商品,如果该日期在表中没有记录说明是第一个商品,并且添加一条数据到获取goods_day_count.
            $goods_day_count = GoodsDayCount::findOne(['day' => date('Ymd', time())]);
            if ($goods_day_count === null) {
                $goods_day_count = new GoodsDayCount();
                $goods_day_count->day = date('Ymd', time());
                $goods_day_count->count = 1;
                $goods_day_count->save();
            } else {
                $count = $goods_day_count->count;
                $goods_day_count->count = $count + 1;
                $goods_day_count->save();
            }
            $model->sn = date('Ymd', time()) . str_pad($goods_day_count->count, 5, "0", STR_PAD_LEFT);
            //加载表单提交的数据
            $model->load($request->post());
            $content->load($request->post());
            //判断数据是否合法
            if ($model->validate() && $content->validate()) {
                //保存数据
                $model->save();
                //获取goods_id赋值给content模型
                $content->goods_id = $model->id;
                $content->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['goods/index']);
            } else {
                var_dump($model->getErrors());
                exit();
            }
        }
        //查询分类数据传到视图 给ztree使用
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        //加载视图
        return $this->render('add', ['model' => $model, 'content' => $content,'nodes'=>json_encode($nodes)]);
    }

    //图片上传
    public function actionLogoUpload()
    {
        //实例化上传文件的类
        $logo_upload = UploadedFile::getInstanceByName('file');
        //生成保存图片的路径
        $file = "/uploads/" . uniqid() . '.' . $logo_upload->extension;
        $result = $logo_upload->saveAs(\Yii::getAlias('@webroot') . $file);
        if ($result) {
            //上传成功返回路径  json格式
            return json_encode(['url' => $file]);
        }
    }

    //Ueditor
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix" => "http://admin.shop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ]
        ];
    }

    public function actionEdit($id){
        //实例化模型
        $model = Goods::findOne(['id'=>$id]);
        $content = GoodsIntro::findOne(['goods_id'=>$id]);
        $request = \Yii::$app->request;
        //判断传输方式
        if ($request->isPost) {
            //加载表单提交的数据
            $model->load($request->post());
            $content->load($request->post());
            //判断数据是否合法
            if ($model->validate() && $content->validate()) {
                //保存数据
                $model->save();
                //获取goods_id赋值给content模型
                $content->goods_id = $model->id;
                $content->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['goods/index']);
            }
        }
        //查询分类数据传到视图 给ztree使用
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        //加载视图
        return $this->render('add', ['model' => $model, 'content' => $content,'nodes'=>json_encode($nodes)]);
    }

    public function actionDelete(){
        $request = \Yii::$app->request;
        $id  = $request->get('id');
        //实例化对象
        $model = Goods::findOne(['id'=>$id]);
        //将状态修改为0
        $model->status = 0;
        if($model->save()){
            return json_encode([
                'res'=>1
            ]);
        }else{
            return json_encode([
                'res'=>0
            ]);
        }
    }

    //从回收站还原删除的数据
    public function actionRestore($id){
        //实例化对象
        $model = Goods::findOne(['id'=>$id]);
        //将状态修改为0
        $model->status = 1;
        $model->save();
        \Yii::$app->session->setFlash('success','还原成功');
        return $this->redirect(['goods/recycle']);
    }

    //rbac的过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
                'except'=>['restore','upload','logo-upload','recycle']
            ]
        ];
    }

}
