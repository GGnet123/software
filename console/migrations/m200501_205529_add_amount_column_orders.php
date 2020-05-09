<?php

use yii\db\Migration;

/**
 * Class m200501_205529_add_amount_column_orders
 */
class m200501_205529_add_amount_column_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order_amounts', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'amount' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order_amounts');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200501_205529_add_amount_column_orders cannot be reverted.\n";

        return false;
    }
    */
}
