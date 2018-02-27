<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        //分页 查询总条数
        $total = Brand::find()->where(['is_deleted'=>0])->count();
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
            if($model->validate()){
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
            if($model->validate()){
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

    //图片上传
    public function actionLogoUpload(){
        //实例化文件上传类
        $imgFile = UploadedFile::getInstanceByName('file');
        //生成保存图片的路径
        $fileName = '/uploads/'.uniqid().'.'.$imgFile->extension;
        $result = $imgFile->saveAs(\Yii::getAlias('@webroot').$fileName);
        if($result){
            //上传图片成功返回路径
            return json_encode(
                ['url'=>$fileName]
            );
        }
    }
}
