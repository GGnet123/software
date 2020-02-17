<?php

use yii\db\Migration;

/**
 * Class m200203_064209_runner_on_table_orders
 */
class m200203_064209_runner_on_table_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('orders','runner',$this->string()->defaultValue("none"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('orders','runner');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200203_064209_runner_on_table_orders cannot be reverted.\n";

        return false;
    }
    */
}
