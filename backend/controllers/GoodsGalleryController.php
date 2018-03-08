<?php

namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\GoodsGallery;
use yii\web\UploadedFile;

class GoodsGalleryController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex($id)
    {
        //根据id查询数据
        $pathes = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        return $this->render('index',['pathes'=>$pathes,'goods_id'=>$id]);
    }

    public function actionLogoUpload(){
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

    public function actionAdd(){
        //实例化模型
        $model = new GoodsGallery();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->goods_id = $request->post('goods_id');
            $model->path = $request->post('url');
            if ($model->validate()){
                if($model->save()){
                    return json_encode(['res'=>1]);
                }
            }
        }
    }

    public function actionDelete(){
        $request = \Yii::$app->request;
        $id = $request->get('id');
        //获取id 对应的对象
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model->delete()){
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
                'except'=>['logo-upload']
            ]
        ];
    }
}
