<table class="table">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach($articlecategorys as $articlecategory):?>
    <tr>
        <td><?=$articlecategory->id?></td>
        <td><?=$articlecategory->name?></td>
        <td><?=$articlecategory->intro?></td>
        <td><?=$articlecategory->sort?></td>
        <td>
            <a href="<?= \yii\helpers\Url::to(['articlecategory/edit','id'=>$articlecategory->id])?>" class="btn btn-primary">修改</a>
            <a href="<?= \yii\helpers\Url::to(['articlecategory/delete','id'=>$articlecategory->id])?>" class="btn btn-primary">删除</a>
        </td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="5"><a href="<?=\yii\helpers\Url::to(['articlecategory/add'])?>" class="btn btn-primary">添加</a></td>
    </tr>
</table>
