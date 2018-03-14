<?php
$form = \yii\bootstrap\ActiveForm::begin([
        'layout'=>'inline',
        'action'=>\yii\helpers\Url::to(['goods/home']),
        'method'=>'get'
]);
echo $form->field($search,'name')->textInput([
        'placeholder'=>'名称:',
]);
echo $form->field($search,'sn')->textInput([
    'placeholder'=>'货号:',
]);
echo $form->field($search,'price_min')->textInput([
    'placeholder'=>'价格(小于):',
]);
echo $form->field($search,'price_max')->textInput([
    'placeholder'=>'价格(大于):',
]);
echo '<button type="submit" class="btn btn-primary">搜索</button>';
\yii\bootstrap\ActiveForm::end();
?>

<table class="table table-hover table-condensed">
    <tr>
        <td>商品名称</td>
        <td>货号</td>
        <td>LOGO图片</td>
        <td>品牌分类</td>
        <td>市场价格</td>
        <td>商品价格</td>
        <td>库存</td>
        <td>是否在售</td>
        <td>添加时间</td>
        <td>浏览次数</td>
        <td>操作</td>
    </tr>
    <?php foreach ($goods as $good):?>
    <tr>
        <td><?=$good->name?></td>
        <td><?=$good->sn?></td>
        <td><img src="<?=$good->logo?>" alt="请等待" width="30px"></td>
        <td><?=$good->brands->name?></td>
        <td><?=$good->market_price?></td>
        <td><?=$good->shop_price?></td>
        <td><?=$good->stock?></td>
        <td><?=$good->is_on_sale?"在售":"下架"?></td>
        <td><?=date('Y-m-d',$good->create_time)?></td>
        <td><?=$good->view_times?></td>
        <td>

            <a href="<?=\yii\helpers\Url::to(['goods-gallery/index','id'=>$good->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-picture"></span>相册</a>

            <?php if (Yii::$app->user->can('goods/edit')):?>
            <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$good->id])?>" class="btn btn-primary">编辑</a>
            <?php endif;?>
            <?php if (Yii::$app->user->can('goods/delete')):?>
            <a href="#" class="btn btn-danger" date="<?=$good->id?>">删除</a>
            <?php endif;?>
        </td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="12">
            <a href="<?=\yii\helpers\Url::to(['goods/recycle'])?>" class="btn btn-primary">回收站</a>
        </td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager
]);
$fileName = \yii\helpers\Url::to(['goods/delete']);
$this->registerJs(
    <<<JS
$(function() {
  $("[date]").click(function() {
    if(confirm("确认删除吗?")){
        var id = $(this).attr("date");
        var del = $(this);
        $.get("{$fileName}",{"id":id},function(val) {
          if(val.res){
              del.closest('tr').remove();
          }else {
              alert("删除失败");
          }
        },"json")
    }
  })
})
JS

);