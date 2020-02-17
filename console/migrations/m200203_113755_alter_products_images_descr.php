<?php

use yii\db\Migration;

/**
 * Class m200203_113755_alter_products_images_descr
 */
class m200203_113755_alter_products_images_descr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('products','image',$this->string());
        $this->addColumn('products','short_description',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('products','image');
        $this->dropColumn('products','short_description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200203_113755_alter_products_images_descr cannot be reverted.\n";

        return false;
    }
    */
}
