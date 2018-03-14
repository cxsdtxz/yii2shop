<table class="table table-hover">
    <tr>
        <td>菜单名称</td>
        <td>菜单地址</td>
        <td>操作</td>
    </tr>
    <?php foreach($menus as $menu):?>
    <tr>
        <td><?= $menu->parent_id != 0 ? "--".$menu->name : $menu->name?></td>
        <td><?= $menu->url?></td>
        <td>
            <?php if (Yii::$app->user->can('menu/edit')):?>
            <a href="<?= \yii\helpers\Url::to(['menu/edit','id'=>$menu->id])?>" class="btn btn-primary">修改</a>
            <?php endif;?>
            <?php if (Yii::$app->user->can('menu/delete')):?>
            <a href="#" class="btn btn-danger" date="<?=$menu->id?>">删除</a>
            <?php endif;?>
        </td>
    </tr>

        <tr>

        </tr>
    <?php endforeach;?>
</table>

<?php
$fileName = \yii\helpers\Url::to(['menu/delete']);
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

