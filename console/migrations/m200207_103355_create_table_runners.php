<?php

use yii\db\Migration;

/**
 * Class m200207_103355_create_table_runners
 */
class m200207_103355_create_table_runners extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('runners',[
            'id'=>$this->primaryKey(),
            'username'=>$this->string()->unique()->notNull(),
            'auth_token'=>$this->string()->notNull()->unique(),
            'password_hash'=>$this->string()->notNull(),
            'rating'=>$this->integer()->defaultValue(50),
            'status_id'=>$this->smallInteger()->defaultValue(2),
            'status'=>$this->string()->defaultValue('offline'),
            'money'=>$this->integer()->defaultValue(0),
            'latitude'=>$this->string()->defaultValue(null),
            'longitude'=>$this->string()->defaultValue(null),
            'busy'=>$this->boolean()->defaultValue(false),
            'order_id'=>$this->integer()->defaultValue(null)
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('runners');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200207_103355_create_table_runners cannot be reverted.\n";

        return false;
    }
    */
}
