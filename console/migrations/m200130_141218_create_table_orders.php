<?php

use yii\db\Migration;

/**
 * Class m200130_141218_create_table_orders
 */
class m200130_141218_create_table_orders extends Migration
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

        $this->createTable('orders',[
            'id'=>$this->primaryKey(),
            'client_name'=>$this->string(),
            'client_address'=>$this->string(),
            'store_id'=>$this->integer(),
            'date'=>$this->string(),
            'is_card'=>$this->boolean(),
            'is_bcc_card'=>$this->boolean(),
            'total'=>$this->integer(),
            'status'=>$this->string()->defaultValue('В ожидании'),
            'status_id'=>$this->string()->defaultValue(1),
            'delivery_type'=>$this->string(),
            'delivery_price'=>$this->integer(),
            'client_phone'=>$this->string(),
            'items'=>$this->string()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('orders');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200130_141218_create_table_orders cannot be reverted.\n";

        return false;
    }
    */
}
