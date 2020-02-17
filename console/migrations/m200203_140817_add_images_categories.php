<?php

use yii\db\Migration;

/**
 * Class m200203_140817_add_images_categories
 */
class m200203_140817_add_images_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('categories','image',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('categories','image');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200203_140817_add_images_categories cannot be reverted.\n";

        return false;
    }
    */
}
