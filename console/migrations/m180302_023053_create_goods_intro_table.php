<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m180302_023053_create_goods_intro_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods_intro', [
            'goods_id' => $this->primaryKey()->comment('商品id'),
            'content'=>$this->text()->comment('商品描述')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods_intro');
    }
}
