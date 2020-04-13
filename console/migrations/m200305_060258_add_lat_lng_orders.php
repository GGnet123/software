<?php

use yii\db\Migration;

/**
 * Class m200305_060258_add_lat_lng_orders
 */
class m200305_060258_add_lat_lng_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('orders','lat', $this->string());
        $this->addColumn('orders','lng', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('orders','lat');
        $this->dropColumn('orders','lng');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200305_060258_add_lat_lng_orders cannot be reverted.\n";

        return false;
    }
    */
}
