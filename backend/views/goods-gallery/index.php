<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>

<ul style="list-style:none">
    <?php foreach ($pathes as $path):?>
    <hr>
    <li><img src="<?=$path->path?>" alt="" width="200px">
        <div style="float:right"><a href="<?=\yii\helpers\Url::to(['goods-gallery/delete','id'=>$path->id])?>" class="btn btn-danger">删除</a></div>
    </li>
    <?php endforeach;?>
</ul>

<?php
/**
 * @var $this \yii\web\View
 */
//引入 css 和js文件
$this->registerCssFile(Yii::getAlias('@web') . '/webuploader-0.1.5/webuploader.css');
$this->registerJsFile(Yii::getAlias('@web') . '/webuploader-0.1.5/webuploader.js', [
    'depends' => \yii\web\JqueryAsset::className()
]);


$logo_upload_file = \yii\helpers\Url::to(['goods-gallery/logo-upload']);
$file = \yii\helpers\Url::to(['goods-gallery/add']);
//js代码
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
        mimeTypes: 'image/*'
    }
});
uploader.on( 'uploadSuccess', function( file,response ) {
    $( '#'+file.id ).addClass('upload-state-done');
    //获取路径
    var fileName = response.url;
    //将图片回显
    $('#img_upload').attr('src',fileName);
    var goods_id = {$goods_id};
    var data = {
        'goods_id':goods_id,
        'url':fileName
    };
    $.post('{$file}',data,function(val) {
        if(val.res == '1'){
            location.reload(true)
        }
    },'json')
});
JS

);
echo '<img id="img_upload">';