<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Menu;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //查询所有数据
        $menus = Menu::find()->all();

        return $this->render('index',['menus'=>$menus]);
    }

    /**
     * @return string|\yii\web\Response
     * 添加菜单
     */
    public function actionAdd(){
        //实例化模型
        $model = new Menu();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['menu/index']);
            }
        }
        //加载视图
        return $this->render('add',['model'=>$model]);
    }

    public function actionEdit($id){
        //实例化对象
        $model = Menu::findOne(['id'=>$id]);
        $model->scenario = Menu::SCENARIO_EDIT;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['menu/index']);
            }
        }
        //加载视图
        return $this->render('add',['model'=>$model]);
    }

    public function actionDelete(){
        //接受id
        $request = \Yii::$app->request;
        $id = $request->get('id');
        //找到对应模型
        $model = Menu::findOne(['id'=>$id]);
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
                'class'=>RbacFilter::class
            ]
        ];
    }
}
