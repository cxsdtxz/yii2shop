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
            <a href="<?= \yii\helpers\Url::to(['menu/edit','id'=>$menu->id])?>" class="btn btn-primary">修改</a>
            <a href="<?= \yii\helpers\Url::to(['menu/delete','id'=>$menu->id])?>" class="btn btn-danger">删除</a>
        </td>
    </tr>

        <tr>

        </tr>
    <?php endforeach;?>
</table>
