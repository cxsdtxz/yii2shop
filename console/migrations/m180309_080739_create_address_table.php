<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180309_080739_create_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(40)->notNull()->comment('收货人'),
            'province'=>$this->string(20)->notNull()->comment('省'),
            'city'=>$this->string(20)->notNull()->comment('城市'),
            'county'=>$this->string(20)->notNull()->comment('县'),
            'address'=>$this->string()->notNull()->comment('详细地址'),
            'tel'=>$this->char(11)->notNull()->comment('手机号'),
            'default'=>$this->char(11)->notNull()->comment('0不设为默认地址 1设为默认地址'),
            'member_id'=>$this->integer()->notNull()->comment('用户id'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('address');
    }
}
