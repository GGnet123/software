<?php

use yii\db\Migration;

/**
 * Class m200204_070340_add_popular_products
 */
class m200204_070340_add_popular_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('products','popular', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('products','popular');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200204_070340_add_popular_products cannot be reverted.\n";

        return false;
    }
    */
}
