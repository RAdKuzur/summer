<?php

use yii\db\Migration;

/**
 * Class m241121_164021_add_student_table
 */
class m241121_164021_add_student_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('student', [
            'id' => $this->primaryKey(),
            'surname' => $this->string(1000),
            'name' => $this->string(1000),
            'patronymic' => $this->string(1000),
            'school_id' => $this->integer()->notNull(),
            'olymp_score' => $this->integer(),
            'tournament_score' => $this->integer(),
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('student');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241121_164021_add_student_table cannot be reverted.\n";

        return false;
    }
    */
}
