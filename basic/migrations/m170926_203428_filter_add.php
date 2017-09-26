<?php

use yii\db\Migration;

class m170926_203428_filter_add extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%filter_article}}','article_dicription',$this->string(255));
    }

    public function safeDown()
    {
        echo "m170926_203428_filter_add cannot be reverted.\n";
        $this->addColumn('{{%filter_article}}','article_dicription',$this->string(255));
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170926_203428_filter_add cannot be reverted.\n";

        return false;
    }
    */
}
