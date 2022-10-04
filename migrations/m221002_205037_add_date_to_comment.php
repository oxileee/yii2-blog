<?php

use yii\db\Migration;

/**
 * Class m221002_205037_add_date_to_comment
 */
class m221002_205037_add_date_to_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('comment', 'date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('comment', 'date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221002_205037_add_date_to_comment cannot be reverted.\n";

        return false;
    }
    */
}
