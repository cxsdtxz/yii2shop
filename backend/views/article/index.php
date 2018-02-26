<table class="table">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>简介</th>
        <th>文章分类</th>
        <th>排序</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
    <tr>
        <td><?=$article->id?></td>
        <td><?=$article->name?></td>
        <td><?=$article->intro?></td>
        <td><?=$article->articleCategory->name?></td>
        <td><?=$article->sort?></td>
        <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['article/edit','id'=>$article->id])?>">修改</a>
            <a href="<?=\yii\helpers\Url::to(['article/delete','id'=>$article->id])?>">删除</a>
        </td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="7"><a href="<?=\yii\helpers\Url::to(['article/add'])?>">添加</a></td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager
]);
