<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'intro')->textarea();
echo $form->field($model, 'logo')->hiddenInput();
//引入css和js
$this->registerCssFile(Yii::getAlias('@web') . '/webuploader-0.1.5/webuploader.css');
$this->registerJsFile(Yii::getAlias('@web') . '/webuploader-0.1.5/webuploader.js', [
    'depends' => \yii\web\JqueryAsset::className()
]);

//显示按钮
echo <<<HTML
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
HTML;

$logo_upload_file = \yii\helpers\Url::to(['brand/logo-upload']);
//JavaScript
$this->registerJs(
    <<<JS
    // 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf: '/web/webuploader-0.1.5/Uploader.swf',

    // 文件接收服务端。
    server: '{$logo_upload_file}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png'
    }
});
//上传成功时触发
uploader.on( 'uploadSuccess', function( file,response ) {
    $( '#'+file.id ).addClass('upload-state-done');
    //获取路径
    var fileName = response.url;
    //将路径赋值给logo
    $('#brand-logo').val(fileName);
    //将图片回显
    $('#img_upload').attr('src',fileName)
});
JS
);
echo "<img id='img_upload' width='100px' src='{$model->logo}'>";
echo $form->field($model, 'sort')->textInput();
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();