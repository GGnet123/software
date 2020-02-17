<?php

use yii\db\Migration;

/**
 * Class m200205_050707_add_column_device_id
 */
class m200205_050707_add_column_device_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','device_id',$this->string()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user','device_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200205_050707_add_column_device_id cannot be reverted.\n";

        return false;
    }
    */
}
