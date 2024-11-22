<?php

use yii\db\Migration;

/**
 * Class m241122_171001_add_squad_student_game
 */
class m241122_171001_add_squad_student_game extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('squad_student_game', [
            'id' => $this->primaryKey(),
            'squad_student_id' => $this->integer()->notNull(),
            'game_id' => $this->integer()->notNull(),
            'score' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('squad_student_game');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241122_171001_add_squad_student_game cannot be reverted.\n";

        return false;
    }
    */
}
