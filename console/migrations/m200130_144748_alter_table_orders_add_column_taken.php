<?php

use yii\db\Migration;

/**
 * Class m200130_144748_alter_table_orders_add_column_taken
 */
class m200130_144748_alter_table_orders_add_column_taken extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('orders','taken','string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('orders','taken');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200130_144748_alter_table_orders_add_column_taken cannot be reverted.\n";

        return false;
    }
    */
}
