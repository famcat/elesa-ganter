<?php

use yii\db\Migration;

class m170920_101311_change extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%types}}','url_img',$this->text());
    }

    public function safeDown()
    {
        echo "m170920_101311_change cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170920_101311_change cannot be reverted.\n";

        return false;
    }
    */
}
