<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Articlecategory;

class ArticlecategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $articlecategorys = Articlecategory::find()->where(['is_deleted'=>0])->all();
        return $this->render('index',['articlecategorys'=>$articlecategorys]);
    }

    public function actionAdd(){
        //创建模型
        $model = new Articlecategory();
        //判断传输方式
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model->is_deleted = 0;
            if ($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['articlecategory/index']);
            }else{
                var_dump($model->getErrors());exit();
            }
        }
        //加载视图
        return $this->render('add',['model'=>$model]);
    }

    public function actionEdit($id){
        //创建模型
        $model = Articlecategory::findOne(['id'=>$id]);
        //判断传输方式
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model->is_deleted = 0;
            if ($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['articlecategory/index']);
            }else{
                var_dump($model->getErrors());exit();
            }
        }
        //加载视图
        return $this->render('add',['model'=>$model]);
    }

    public function actionDelete(){
        //接受id
        $request = \Yii::$app->request;
        $id = $request->get('id');
        //修改is_deleted
        $model = Articlecategory::findOne(['id'=>$id]);
        $model->is_deleted = 1;
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

    //rbac的过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
            ]
        ];
    }
}
