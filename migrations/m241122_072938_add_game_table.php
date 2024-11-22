<?php

use yii\db\Migration;

/**
 * Class m241122_072938_add_game_table
 */
class m241122_072938_add_game_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241122_072938_add_game_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241122_072938_add_game_table cannot be reverted.\n";

        return false;
    }
    */
}
