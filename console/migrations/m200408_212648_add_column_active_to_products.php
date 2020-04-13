<?php

use yii\db\Migration;

/**
 * Class m200408_212648_add_column_active_to_products
 */
class m200408_212648_add_column_active_to_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('products','is_active', $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('products', 'is_active');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200408_212648_add_column_active_to_products cannot be reverted.\n";

        return false;
    }
    */
}
