<?php
/* @var $this yii\web\View */
?>
<table class="table">
    <tr>
        <td>用户名</td>
        <td>邮箱</td>
        <td>创建时间</td>
        <td>更新时间</td>
        <td>最后登录时间</td>
        <td>最后登录ip</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach ($admins as $admin):?>
    <tr>
        <td><?= $admin->username?></td>
        <td><?= $admin->email?></td>
        <td><?= date('Y-m-d H:i:s',$admin->created_at)?></td>
        <td><?= date('Y-m-d H:i:s',$admin->updated_at)?></td>
        <td><?= date('Y-m-d H:i:s',$admin->last_login_time)?></td>
        <td><?= $admin->last_login_ip?></td>
        <td><?= $admin->status ? "禁用":"启用"?></td>
        <td>
            <a href="<?= \yii\helpers\Url::to(['admin/edit','id'=>$admin->id])?>" class="btn btn-primary">修改信息</a>
            <a href="<?= \yii\helpers\Url::to(['admin/delete','id'=>$admin->id])?>" class="btn btn-danger">删除</a>
            <a href="<?= \yii\helpers\Url::to(['admin/re-password','id'=>$admin->id])?>" class="btn btn-danger">重置密码</a>
        </td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="8"><a href="<?=\yii\helpers\Url::to(['admin/add'])?>" class="btn btn-primary">添加</a></td>
    </tr>
</table>
