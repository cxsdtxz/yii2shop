<table class="table">
    <tr>
        <th>名称</th>
        <th>上级分类id</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach($goodsCategorys as $goodsCategory):?>
    <tr>
        <td><?= $goodsCategory->name?></td>
        <td><?= $goodsCategory->parent_id?></td>
        <td><?= $goodsCategory->intro?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['goods-category/edit','id'=>$goodsCategory->id])?>" class="btn btn-primary">修改</a>
            <a href="<?=\yii\helpers\Url::to(['goods-category/delete','id'=>$goodsCategory->id])?>" class="btn btn-danger">删除</a>
        </td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="9"><a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-primary">添加</a></td>
    </tr>
</table>