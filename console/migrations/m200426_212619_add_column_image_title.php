<?php

use yii\db\Migration;

/**
 * Class m200426_212619_add_column_image_title
 */
class m200426_212619_add_column_image_title extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('stores','image', $this->string());
        $this->addColumn('stores','title', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('stores', 'image');
        $this->dropColumn('stores', 'title');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200426_212619_add_column_image_title cannot be reverted.\n";

        return false;
    }
    */
}
