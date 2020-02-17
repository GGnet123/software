<?php

use yii\db\Migration;

/**
 * Class m200203_094610_add_categories
 */
class m200203_094610_add_categories extends Migration
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

        $this->createTable('categories',[
            'id'=>$this->primaryKey(),
            'title'=>$this->string()->notNull()
        ],$tableOptions);
        $this->addColumn('products','category_id', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('categories');
        $this->dropColumn('products','category_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200203_094610_add_categories cannot be reverted.\n";

        return false;
    }
    */
}
