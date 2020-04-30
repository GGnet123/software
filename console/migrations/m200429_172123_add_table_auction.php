<?php

use yii\db\Migration;

/**
 * Class m200429_172123_add_table_auction
 */
class m200429_172123_add_table_auction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('auctions', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'image' => $this->string()->notNull(),
            'price' => $this->integer()->defaultValue(25),
            'is_active' => $this->boolean()->defaultValue(false)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('auctions');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200429_172123_add_table_auction cannot be reverted.\n";

        return false;
    }
    */
}
