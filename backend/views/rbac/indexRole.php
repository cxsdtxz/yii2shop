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
            <a href="<?=\yii\helpers\Url::to(['rbac/edit-role','name'=>$role->name])?>" class="btn btn-primary">修改</a>
            <a href="<?=\yii\helpers\Url::to(['rbac/delete-role','name'=>$role->name])?>" class="btn btn-danger">删除</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>