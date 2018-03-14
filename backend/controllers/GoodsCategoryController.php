<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\GoodsCategory;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $goodsCategorys = GoodsCategory::find()->all();
        return $this->render('index',['goodsCategorys'=>$goodsCategorys]);
    }

    public function actionAdd(){
        //实例化goodscategory类的对象
        $model = new GoodsCategory();
        $request = \Yii::$app->request;
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            if($model->validate()){
                //保存数据  判断是否为根节点
                if ($model->parent_id){
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods-category/index']);
            }
        }
        //查询分类数据传到视图 给ztree使用
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        //加载视图
        return $this->render('add',['model'=>$model,'nodes'=>json_encode($nodes)]);
    }

    public function actionEdit($id){
        //实例化goodscategory类的对象
        $model = GoodsCategory::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            if($model->validate()){
                //保存数据
                if ($model->parent_id){
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    if($model->getOldAttribute('parent_id')==0){
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }
                }
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods-category/index']);
            }
        }
        //查询分类数据传到视图
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        //加载视图
        return $this->render('add',['model'=>$model,'nodes'=>json_encode($nodes)]);
    }

    public function actionDelete(){
        //接受id
        $request = \Yii::$app->request;
        $id = $request->get('id');
        //根据id 找到对应的数据
        $model = GoodsCategory::findOne(['id'=>$id]);
        //判断是否删除的是根节点,如果是用deleteWithChildren删除
        if($model->parent_id){
            $model->delete();
        }else{
            $model->deleteWithChildren();
        }
        return json_encode([
            'res'=>1
        ]);
    }

    //rbac的过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
            ]
        ];
    }

    //ztree插件测试
//    public function actionTest(){
//        //查询数据
//        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
//        //将数据转为json格式传到视图
//        return $this->renderPartial('ztree',['nodes'=>json_encode($nodes)]);
//    }

}
