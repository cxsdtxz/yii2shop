<?php

namespace backend\controllers;

use app\models\Article;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //分页
        $total = Article::find()->where(['is_deleted'=>0])->count();
        $pageSize = 3;
        $pager = new Pagination();
        $pager->totalCount = $total;
        $pager->defaultPageSize = $pageSize;

        $articles = Article::find()->where(['is_deleted'=>0])->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['articles'=>$articles,'pager'=>$pager]);
    }

    public function actionAdd(){
        //创建模型
        $model = new Article();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model->is_deleted = 0;
            $model->create_time = time();
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());die();
            }
        }
        //加载视图
        return $this->render('add',['model'=>$model]);
    }

    public function actionEdit($id){
        //创建模型
        $model = Article::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model->is_deleted = 0;
            $model->create_time = time();
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());die();
            }
        }
        //加载视图
        return $this->render('add',['model'=>$model]);
    }

    public function actionDelete($id){
        $model = Article::findOne(['id'=>$id]);
        $model->is_deleted = 1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article/index']);
    }
}
