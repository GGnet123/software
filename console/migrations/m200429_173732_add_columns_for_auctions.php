<?php

use yii\db\Migration;

/**
 * Class m200429_173732_add_columns_for_auctions
 */
class m200429_173732_add_columns_for_auctions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'bonuses', $this->integer()->defaultValue(0));
        $this->addColumn('user', 'phone_number', $this->string());
        $this->addColumn('auctions', 'participants_ids', $this->string());
        $this->addColumn('auctions', 'winner_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'bonuses');
        $this->dropColumn('user', 'phone_number');
        $this->dropColumn('auctions', 'participants_ids');
        $this->dropColumn('auctions', 'winner_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200429_173732_add_columns_for_auctions cannot be reverted.\n";

        return false;
    }
    */
}
