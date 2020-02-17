<?php

use yii\db\Migration;

/**
 * Class m200130_131454_alter_table_user
 */
class m200130_131454_alter_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user','created_at','string');
        $this->alterColumn('user','updated_at','string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('user','created_at','integer');
        $this->alterColumn('user','updated_at','integer');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200130_131454_alter_table_user cannot be reverted.\n";

        return false;
    }
    */
}
