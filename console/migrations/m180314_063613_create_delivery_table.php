<?php

use yii\db\Migration;

/**
 * Handles the creation of table `delivery`.
 */
class m180314_063613_create_delivery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('delivery', [
            'delivery_id' => $this->primaryKey(),
            'delivery_name'=>$this->string()->notNull()->comment('配送方式名称'),
            'delivery_price'=>$this->float()->notNull()->comment('配送方式价格'),
            'delivery_intro'=>$this->string()->notNull()->comment('配送方式详情'),
            /**
             * delivery_id	int	配送方式id
            delivery_name	varchar	配送方式名称
            delivery_price	float	配送方式价格
             */
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('delivery');
    }
}
