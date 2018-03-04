<table class="table">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>描述</th>
        <th>logo</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brands as $brand):?>
    <tr>
        <td><?=$brand['id']?></td>
        <td><?=$brand['name']?></td>
        <td><?=$brand['intro']?></td>
        <td><img src="<?=$brand['logo']?>" alt="请等待" width="50px"></td>
        <td><?=$brand['sort']?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$brand['id']])?>" class="btn btn-primary">修改</a>
            <a href="<?=\yii\helpers\Url::to(['brand/delete','id'=>$brand['id']])?>" class="btn btn-danger">删除</a>
        </td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="6">
            <a class="btn btn-primary" href="<?=\yii\helpers\Url::to(['brand/add'])?>">添加</a>
        </td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager
]);
