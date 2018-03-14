<table class="table table-hover">
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
            <?php if (Yii::$app->user->can('goods-category/edit')):?>
            <a href="<?=\yii\helpers\Url::to(['goods-category/edit','id'=>$goodsCategory->id])?>" class="btn btn-primary">修改</a>
            <?php endif;?>
            <?php if (Yii::$app->user->can('goods-category/delete')):?>
            <a href="#" class="btn btn-danger" date="<?=$goodsCategory->id?>">删除</a>
            <?php endif;?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
$fileName = \yii\helpers\Url::to(['goods-category/delete']);
$this->registerJs(
    <<<JS
$(function() {
  $("[date]").click(function() {
    if(confirm('确认删除吗?')){
        var id = $(this).attr('date');
        var del = $(this);
        $.get("{$fileName}",{'id':id},function(val) {
          if(val.res == 1){
              del.closest('tr').remove();
          }else {
              alert('删除失败')
          }
        },'json')
    }
  })
})
JS

);

