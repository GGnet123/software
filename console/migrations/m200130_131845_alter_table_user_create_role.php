<?php

use yii\db\Migration;

/**
 * Class m200130_131845_alter_table_user_create_role
 */
class m200130_131845_alter_table_user_create_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','role','string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user','role');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200130_131845_alter_table_user_create_role cannot be reverted.\n";

        return false;
    }
    */
}
