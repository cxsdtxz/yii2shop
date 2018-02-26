<?php

namespace backend\controllers;

use app\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //分页 查询总条数
        $total = Brand::find()->count();
        $pageSize = 3;
        $pager = new Pagination();
        $pager->defaultPageSize = $pageSize;
        $pager->totalCount = $total;
        //查询is_delete为0的数据
        $brands = Brand::find()->where(['is_deleted'=>0])->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['brands'=>$brands,'pager'=>$pager]);
    }


    public function actionAdd(){
        //创建模型
        $model = new Brand();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //上传文件
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                if($model->imgFile){
                    //生成图片保存路径
                    $file = '/uploads/'.uniqid().'.'.$model->imgFile->extension;
                    if($model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0)){
                       $model->logo = $file;
                    }
                }
                //默认显示,is_deleted 为0
                $model->is_deleted = 0;
                //保存数据
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());die();
            }
        }
        //加载视图
        return $this->render('add',['model'=>$model]);
    }

    public function actionEdit($id){
        //创建模型
        $model = Brand::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //上传文件
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                if($model->imgFile){
                    //生成图片保存路径
                    $file = '/uploads/'.uniqid().'.'.$model->imgFile->extension;
                    if($model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0)){
                        $model->logo = $file;
                    }
                }
                //默认显示,is_deleted 为0
                $model->is_deleted = 0;
                //保存数据
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());die();
            }
        }
        //加载视图
        return $this->render('add',['model'=>$model]);
    }

    public function actionDelete($id){
        //将is_deleted改为1 表示删除
        $model = Brand::findOne(['id'=>$id]);
        $model->is_deleted = 1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['brand/index']);
    }

}
