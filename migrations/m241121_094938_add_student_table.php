<?php

use yii\db\Migration;

/**
 * Class m241121_094938_add_student_table
 */
class m241121_094938_add_student_table extends Migration
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
        echo "m241121_094938_add_student_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241121_094938_add_student_table cannot be reverted.\n";

        return false;
    }
    */
}
