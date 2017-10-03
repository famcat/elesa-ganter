<?php

use yii\db\Migration;

class m171003_080603_add_color_field extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%color_list}}','color_hex',$this->string(255));
    }

    public function safeDown()
    {
        echo "m171003_080603_add_color_field cannot be reverted.\n";
        $this->dropColumn('{{%color_list}}','color_hex');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171003_080603_add_color_field cannot be reverted.\n";

        return false;
    }
    */
}
