<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //分页
        $total = Article::find()->where(['is_deleted' => 0])->count();
        $pageSize = 3;
        $pager = new Pagination();
        $pager->totalCount = $total;
        $pager->defaultPageSize = $pageSize;

        $articles = Article::find()->where(['is_deleted' => 0])->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index', ['articles' => $articles, 'pager' => $pager]);
    }

    public function actionAdd()
    {
        //创建模型  文章表模型和详情表
        $model = new Article();
        $content = new ArticleDetail();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            $content->load($request->post());
            $model->is_deleted = 0;
            $model->create_time = time();
            if ($model->validate() && $content->validate()) {
                $model->save();
                $article_id = $model->id;
                $content->article_id = $article_id;
                $content->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['article/index']);
            } else {
                var_dump($model->getErrors());
                die();
            }
        }
        //加载视图
        return $this->render('add', ['model' => $model, 'content' => $content]);
    }

    public function actionEdit($id)
    {
        //创建模型
        $model = Article::findOne(['id' => $id]);
        $content = ArticleDetail::findOne(['article_id' => $id]);

        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            $content->load($request->post());
            if ($model->validate() && $content->validate()) {
                $model->save();
                $content->save();
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['article/index']);
            } else {
                var_dump($model->getErrors());
                die();
            }
        }
        //加载视图
        return $this->render('add', ['model' => $model, 'content' => $content]);
    }

    public function actionDelete($id)
    {
        $model = Article::findOne(['id' => $id]);
        $model->is_deleted = 1;
        $model->save();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['article/index']);
    }

    //富文本编辑器
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.shop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ]
        ];
    }
}
