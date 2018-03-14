<table class="table table-hover">
    <tr>
        <td>名称</td>
        <td>描述</td>
        <td>操作</td>
    </tr>
    <?php foreach($roles as $role):?>
    <tr>
        <td><?=$role->name?></td>
        <td><?=$role->description?></td>
        <td>
            <?php if (Yii::$app->user->can('rbac/edit-role')):?>
            <a href="<?=\yii\helpers\Url::to(['rbac/edit-role','name'=>$role->name])?>" class="btn btn-primary">修改</a>
            <?php endif;?>
            <?php if (Yii::$app->user->can('rbac/delete-role')):?>
            <a href="#" class="btn btn-danger" date="<?=$role->name?>">删除</a>
            <?php endif;?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$fileName = \yii\helpers\Url::to(['rbac/delete-role']);
$this->registerJs(
        <<<JS
$(function() {
  $("[date]").click(function() {
    if(confirm('确认删除吗?')){
        var name = $(this).attr('date');
        var del = $(this);
        $.get("{$fileName}",{'name':name},function(val) {
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