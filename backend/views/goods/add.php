<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'logo')->hiddenInput();
//引入 css 和js文件
$this->registerCssFile(Yii::getAlias('@web') . '/webuploader-0.1.5/webuploader.css');
$this->registerJsFile(Yii::getAlias('@web') . '/webuploader-0.1.5/webuploader.js', [
    'depends' => \yii\web\JqueryAsset::className()
]);
//html
echo '<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>';


$logo_upload_file = \yii\helpers\Url::to(['goods/logo-upload']);
//js
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

//上传成功时触发
uploader.on( 'uploadSuccess', function( file,response ) {
    $( '#'+file.id ).addClass('upload-state-done');
    //获取路径
    var fileName = response.url;
    //将路径赋值给logo
    $('#goods-logo').val(fileName);
    //将图片回显
    $('#img_upload').attr('src',fileName)
});
JS
);
echo "<img id='img_upload' width='100px' src='{$model->logo}'>";


echo $form->field($model, 'goods_category_id')->hiddenInput();
//引入js 和 css  ztree插件
//$this->registerCssFile("@web/ztree/css/demo.css");
$this->registerCssFile("@web/ztree/css/zTreeStyle/zTreeStyle.css");
$this->registerJsFile("@web/ztree/js/jquery.ztree.core.js", [
    'depends' => \yii\web\JqueryAsset::class
]);

//HTML代码
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';

//js代码
$this->registerJs(
    <<<JS
var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {
		        onClick: function(event, treeId, treeNode) {
		             //alert(treeNode.tId + ", " + treeNode.name);
		             $('#goods-goods_category_id').val(treeNode.id)
		        }
	        }
        };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = {$nodes};
   zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
   zTreeObj.expandAll(true);//展开所有分类
   
JS
);

echo $form->field($model, 'brand_id')->dropDownList($model->brand);
echo $form->field($model, 'market_price')->textInput();
echo $form->field($model, 'shop_price')->textInput();
echo $form->field($model, 'stock')->textInput();
echo $form->field($model, 'is_on_sale', ['inline' => 1])->radioList([
    1 => '在售', 0 => '下架'
]);
echo $form->field($model, 'sort')->textInput();
//Ueditor
echo $form->field($content, 'content')->widget('kucha\ueditor\UEditor', [
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' => 'en', //中文为 zh-cn
    ]
]);

echo '<button type="submit" class="btn btn-primary">提交</button>';

\yii\bootstrap\ActiveForm::end();