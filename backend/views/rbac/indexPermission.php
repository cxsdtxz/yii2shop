<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($permissions as $permission):?>
    <tr>
        <td><?= $permission->name?></td>
        <td><?= $permission->description?></td>
        <td>
            <?php if (Yii::$app->user->can('rbac/edit-permission')):?>
            <a href="<?=\yii\helpers\Url::to(['rbac/edit-permission','name'=>$permission->name])?>" class="btn btn-primary">修改</a>
            <?php endif;?>
            <?php if (Yii::$app->user->can('rbac/delete-permission')):?>
            <a href="#" class="btn btn-danger" date="<?=$permission->name?>">删除</a>
            <?php endif;?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>


<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile("@web/DataTables-1.10.15/media/css/jquery.dataTables.css");

$this->registerJsFile("@web/DataTables-1.10.15/media/js/jquery.dataTables.js",[
        'depends'=>\yii\web\JqueryAsset::class
]);

$fileName = \yii\helpers\Url::to(['rbac/delete-permission']);
$this->registerJs(
    <<<JS
$(document).ready( function () {
    $('#table_id_example').DataTable();
    
} );
$('#table_id_example').DataTable({
    language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
});

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