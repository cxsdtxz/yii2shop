<table class="table table-hover table-condensed">
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
            <?php if(Yii::$app->user->can('brand/edit')):?>
            <a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$brand['id']])?>" class="btn btn-primary">修改</a>
            <?php endif;?>
            <?php if(Yii::$app->user->can('brand/delete')):?>
            <a href="#" class="btn btn-danger" date="<?=$brand->id?>">删除</a>
            <?php endif;?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager
]);
$fileName = \yii\helpers\Url::to(['brand/delete']);
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

