<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180226_105844_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->string()->comment('简介'),
            'article_category_id'=>$this->integer()->notNull()->comment('文章分类id'),
            'sort'=>$this->integer()->notNull()->comment('排序'),
            'is_deleted'=>$this->integer(1)->notNull()->comment('状态 0正常1删除'),
            'create_time'=>$this->integer()->notNull()->comment('创建时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
