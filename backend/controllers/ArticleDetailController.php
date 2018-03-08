<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\ArticleDetail;

class ArticleDetailController extends \yii\web\Controller
{
    public function actionRead($id){
        //实例化详情表的模型
        $content = ArticleDetail::findOne(['article_id'=>$id]);

        //加载视图
        return $this->render('read',['content'=>$content]);
    }

    //rbac的过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class
            ]
        ];
    }

}
