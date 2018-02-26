<?php

namespace backend\controllers;

use app\models\Articlecategory;

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

    public function actionDelete($id){
        //修改is_deleted
        $model = Articlecategory::findOne(['id'=>$id]);
        $model->is_deleted = 1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['articlecategory/index']);
    }
}
