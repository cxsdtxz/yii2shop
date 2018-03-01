<?php

namespace backend\controllers;

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
                //保存数据
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
        //加载视图
        return $this->render('add',['model'=>$model]);
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
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods-category/index']);
            }
        }
        //加载视图
        return $this->render('add',['model'=>$model]);
    }

    public function actionDelete($id){
        //根据id 找到对应的数据
        $model = GoodsCategory::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['goods-category/index']);
    }

}
