<?php

use yii\db\Migration;

/**
 * Class m241121_163951_add_schools_table
 */
class m241121_163951_add_schools_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('school', [
            'id' => $this->primaryKey(),
            'name' => $this->string(1000)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('school');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241121_163951_add_schools_table cannot be reverted.\n";

        return false;
    }
    */
}
