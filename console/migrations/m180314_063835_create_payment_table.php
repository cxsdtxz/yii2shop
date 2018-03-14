<?php

use yii\db\Migration;

/**
 * Handles the creation of table `payment`.
 */
class m180314_063835_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment', [
            'payment_id' => $this->primaryKey(),
            'payment_name'=>$this->string()->notNull()->comment('支付方式名称'),
            'payment_intro'=>$this->string()->notNull()->comment('支付方式详情'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('payment');
    }
}
