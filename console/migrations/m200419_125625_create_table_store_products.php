<?php

use yii\db\Migration;

/**
 * Class m200419_125625_create_table_store_products
 */
class m200419_125625_create_table_store_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('store_products', [
            'id' => $this->primaryKey(),
            'store_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'product_title' => $this->string()->notNull(),
            'amount' => $this->integer()->defaultValue(0),
            'price'=>$this->integer()->defaultValue(0),
            'grams' =>$this->float()->defaultValue(0.0),
        ], $tableOptions);

        $this->createTable('stores', [
            'id' => $this->primaryKey(),
            'address' => $this->string()->notNull(),
            'lat' =>$this->float()->defaultValue(0.0),
            'lng' =>$this->float()->defaultValue(0.0),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('store_products');
        $this->dropTable('stores');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200419_125625_create_table_store_products cannot be reverted.\n";

        return false;
    }
    */
}
