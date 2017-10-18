<?php

use yii\db\Migration;

class m171018_094123_color_code_add extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%color_code}}','color_description',$this->string(255));
    }

    public function safeDown()
    {
        echo "m171018_094123_color_code_add cannot be reverted.\n";
        $this->dropColumn('{{%color_code}}','color_description');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171018_094123_color_code_add cannot be reverted.\n";

        return false;
    }
    */
}
