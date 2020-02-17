<?php

use yii\db\Migration;

/**
 * Class m200204_044015_add_column_popular_categories
 */
class m200204_044015_add_column_popular_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('categories','popular', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('categories','popular');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200204_044015_add_column_popular_categories cannot be reverted.\n";

        return false;
    }
    */
}
