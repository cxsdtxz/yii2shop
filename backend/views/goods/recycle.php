<table class="table">
    <a href="<?=\yii\helpers\Url::to(['goods/index'])?>" class="btn btn-primary">回到首页</a>
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
                <a href="<?=\yii\helpers\Url::to(['goods/restore','id'=>$good->id])?>" class="btn btn-primary">还原</a>
            </td>
        </tr>
    <?php endforeach;?>

</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);