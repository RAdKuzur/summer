<?php

use yii\db\Migration;

/**
 * Class m241122_065719_add_squad_student_table
 */
class m241122_065719_add_squad_student_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('squad_student', [
            'id' => $this->primaryKey(),
            'student_id' => $this->integer()->notNull(),
            'squad_id' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('squad_student');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241122_065719_add_squad_student_table cannot be reverted.\n";

        return false;
    }
    */
}
