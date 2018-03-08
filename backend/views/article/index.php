<table class="table table-hover">
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
            <a href="<?=\yii\helpers\Url::to(['article/edit','id'=>$article->id])?>" class="btn btn-primary">修改</a>
            <a href="#" class="btn btn-danger" date="<?= $article->id?>">删除</a>
            <a href="<?=\yii\helpers\Url::to(['article-detail/read','id'=>$article->id])?>" class="btn btn-primary">查看</a>
        </td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="7"><a href="<?=\yii\helpers\Url::to(['article/add'])?>" class="btn btn-primary">添加</a></td>
    </tr>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager
]);
$fileName = \yii\helpers\Url::to(['article/delete']);
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
