<?php

use yii\db\Migration;

/**
 * Class m241121_164011_add_squad_table
 */
class m241121_164011_add_squad_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('squad', [
            'id' => $this->primaryKey(),
            'name' => $this->string(1000)->notNull(),
            'total_score' => $this->integer()->notNull(),
            'tournament_id' => $this->integer()->notNull(),
            'school_id' => $this->integer(),
            'win' => $this->integer(),// nullable field
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('squad');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241121_164011_add_squad_table cannot be reverted.\n";

        return false;
    }
    */
}
