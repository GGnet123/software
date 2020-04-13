<?php

use yii\db\Migration;

/**
 * Class m200408_200327_add_column_slug_to_categories
 */
class m200408_200327_add_column_slug_to_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('categories','slug', $this->string()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('categories','slug');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200408_200327_add_column_slug_to_categories cannot be reverted.\n";

        return false;
    }
    */
}
