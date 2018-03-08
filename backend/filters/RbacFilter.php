<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/7
 * Time: 14:27
 */

namespace backend\filters;


use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        //判断是否有权限使用该操作
        if(!\Yii::$app->user->can($action->uniqueId)){
            //判断是否登录
            if(\Yii::$app->user->isGuest){
                //跳转到登录界面,加一个send(),防止返回true,因为redirect返回的是一个对象,直接return会判断为true.
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            //抛出错误
            throw new HttpException(403,'您的权限不足');
        }
        return true;
    }
}