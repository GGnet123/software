<?php

use yii\db\Migration;

/**
 * Class m200207_115926_add_column_device_id_runner
 */
class m200207_115926_add_column_device_id_runner extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200207_115926_add_column_device_id_runner cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200207_115926_add_column_device_id_runner cannot be reverted.\n";

        return false;
    }
    */
}
