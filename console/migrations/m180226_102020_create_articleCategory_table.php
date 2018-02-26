<?php

use yii\db\Migration;

/**
 * Handles the creation of table `articleCategory`.
 */
class m180226_102020_create_articleCategory_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('articleCategory', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->string()->notNull()->comment('简介'),
            'sort'=>$this->integer()->notNull()->comment('排序'),
            'is_deleted'=>$this->integer(1)->notNull()->comment('状态 0正常1删除'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('articleCategory');
    }
}
