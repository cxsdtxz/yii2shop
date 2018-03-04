<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m180304_024149_create_admin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull()->comment('自动登录秘钥'),
            'password_hash' => $this->string()->notNull()->comment('密码'),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique()->comment('邮箱'),

            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('0启用1禁用'),
            'created_at' => $this->integer()->notNull()->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'last_login_time' => $this->string()->notNull()->comment('最后登录时间'),
            'last_login_ip' => $this->string()->notNull()->comment('最后登录ip'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('admin');
    }
}
