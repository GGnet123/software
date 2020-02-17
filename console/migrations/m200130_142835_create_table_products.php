<?php

use yii\db\Migration;

/**
 * Class m200130_142835_create_table_products
 */
class m200130_142835_create_table_products extends Migration
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

        $this->createTable('products',[
            'id'=>$this->primaryKey(),
            'title'=>$this->string(),
            'price'=>$this->string(),
            'barcode'=>$this->string()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('products');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200130_142835_create_table_products cannot be reverted.\n";

        return false;
    }
    */
}
