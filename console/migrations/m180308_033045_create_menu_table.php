<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m180308_033045_create_menu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull()->comment('菜单名称'),
            'parent_id'=>$this->integer()->notNull()->comment('上级id'),
            'url'=>$this->string()->notNull()->comment('上级id'),
            'sourt'=>$this->integer()->notNull()->comment('排序'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('menu');
    }
}
