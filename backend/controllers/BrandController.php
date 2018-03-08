<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use function Couchbase\fastlzCompress;
use yii\data\Pagination;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class BrandController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        //分页 查询总条数
        $total = Brand::find()->where(['is_deleted' => 0])->count();
        $pageSize =6;
        $pager = new Pagination();
        $pager->defaultPageSize = $pageSize;
        $pager->totalCount = $total;
        //查询is_delete为0的数据
        $brands = Brand::find()->where(['is_deleted' => 0])->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index', ['brands' => $brands, 'pager' => $pager]);
    }


    public function actionAdd()
    {
        //创建模型
        $model = new Brand();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //默认显示,is_deleted 为0
                $model->is_deleted = 0;
                //保存数据
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['brand/index']);
            }
        }
        //加载视图
        return $this->render('add', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        //创建模型
        $model = Brand::findOne(['id' => $id]);
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //默认显示,is_deleted 为0
                $model->is_deleted = 0;
                //保存数据
                $model->save();
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['brand/index']);
            } else {
                var_dump($model->getErrors());
                die();
            }
        }
        //加载视图
        return $this->render('add', ['model' => $model]);
    }

    public function actionDelete()
    {
        //接受id
        $request = \Yii::$app->request;
        $id = $request->get('id');
        //将is_deleted改为1 表示删除
        $model = Brand::findOne(['id' => $id]);
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

    //图片上传
    public function actionLogoUpload()
    {
        //实例化文件上传类
        $imgFile = UploadedFile::getInstanceByName('file');
        //生成保存图片的路径
        $fileName = '/uploads/' . uniqid() . '.' . $imgFile->extension;
        $result = $imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName);
        if ($result) {

            // 需要填写你的 Access Key 和 Secret Key
            $accessKey = "RNAaIVEfkDsZt0HenvPu8MPyOKhCkfFB20JgNrbm";
            $secretKey = "mUVkI_UPDrWlTL1QW7rWSryhrmx5XeNPwrUkwKiv";
            $bucket = "yii2shop";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot').$fileName;
            // 上传到七牛后保存的文件名
            $key = $fileName;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//            echo "\n====> putFile result: \n";
            if ($err !== null) {
                var_dump($err);
            } else {

                return json_encode([
                    'url'=>"http://p4w3vwllj.bkt.clouddn.com/{$fileName}"
                ]);
            }
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
