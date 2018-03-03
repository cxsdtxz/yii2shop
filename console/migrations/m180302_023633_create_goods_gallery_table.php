<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_gallery`.
 */
class m180302_023633_create_goods_gallery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods_gallery', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->notNull()->comment('商品id'),
            'path'=>$this->string()->comment('图片地址')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods_gallery');
    }
}
