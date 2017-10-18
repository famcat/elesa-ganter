<?php

use yii\db\Migration;

class m171015_143904_color_data extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%filter_article}}','color_attribute',$this->string(255));
    }

    public function safeDown()
    {
        echo "m171015_143904_color_data cannot be reverted.\n";
        $this->dropColumn('{{%filter_article}}','color_attribute');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171015_143904_color_data cannot be reverted.\n";

        return false;
    }
    */
}
