<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/11
 * Time: 11:50
 */

namespace frontend\aliyun;


use yii\base\Component;

class SmsHandler extends Component
{
    public $ak;
    public $sk;
    public $sign;
    public $template;
    private $_tel;
    private $_params;

    public function setTel($tel){
        $this->_tel = $tel;
        return $this;
    }

    public function setParams($params){
        $this->_params = $params;
        return $this;
    }

    public function send(){
        $params = [
            'PhoneNumbers'=>$this->_tel,
            'SignName'=>$this->sign,
            'TemplateCode'=>$this->template,
            'TemplateParam'=>$this->_params
        ];
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $this->ak,
            $this->sk,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );

        return ($content->Message == 'OK' && $content->Code == 'OK');
    }
}