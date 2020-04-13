<?php

use yii\db\Migration;

/**
 * Class m200322_164709_add_verification_code_to_runners
 */
class m200322_164709_add_verification_code_to_runners extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('runners', 'code', $this->string()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('runners', 'code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200322_164709_add_verification_code_to_runners cannot be reverted.\n";

        return false;
    }
    */
}
