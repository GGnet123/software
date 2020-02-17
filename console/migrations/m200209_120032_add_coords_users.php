<?php

use yii\db\Migration;

/**
 * Class m200209_120032_add_coords_users
 */
class m200209_120032_add_coords_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','lat',$this->string());
        $this->addColumn('user','lng',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user','lat');
        $this->dropColumn('user','lng');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200209_120032_add_coords_users cannot be reverted.\n";

        return false;
    }
    */
}
