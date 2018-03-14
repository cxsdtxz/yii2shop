<table class="table table-hover">
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
            <?php if (Yii::$app->user->can('articlecategory/edit')):?>
            <a href="<?= \yii\helpers\Url::to(['articlecategory/edit','id'=>$articlecategory->id])?>" class="btn btn-primary">修改</a>
            <?php endif;?>
            <?php if (Yii::$app->user->can('articlecategory/delete')):?>
            <a href="#" class="btn btn-danger" date="<?=$articlecategory->id?>">删除</a>
            <?php endif;?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$fileName = \yii\helpers\Url::to(['articlecategory/delete']);
$this->registerJs(
        <<<JS
$(function() {
  $("[date]").click(function() {
    if(confirm("确认删除吗?")){
        var id = $(this).attr('date');
        var del = $(this);
        $.get("{$fileName}",{"id":id},function(val) {
          if (val.res == 1){
              del.closest('tr').remove();
          }else {
              alert('删除失败')
          }
        },"json")
    }
  })
})
JS

);