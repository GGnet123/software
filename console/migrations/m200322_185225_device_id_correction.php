<?php

use yii\db\Migration;

/**
 * Class m200322_185225_device_id_correction
 */
class m200322_185225_device_id_correction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('user','device_id');
        $this->addColumn('runners','device_id', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('runners','device_id');
        $this->addColumn('user','device_id', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200322_185225_device_id_correction cannot be reverted.\n";

        return false;
    }
    */
}
