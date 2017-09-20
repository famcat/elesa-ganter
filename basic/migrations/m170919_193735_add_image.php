<?php

use yii\db\Migration;

class m170919_193735_add_image extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%types}}','url_img',$this->string(255));
        $this->addColumn('{{%types}}','expires',$this->timestamp());
        $this->addColumn('{{%productions}}','expires',$this->timestamp());
        $this->addColumn('{{%schema_productions}}','expires',$this->timestamp());
    }

    public function safeDown()
    {
        echo "m170919_193735_add_image cannot be reverted.\n";
        $this->dropColumn('{{%types}}','url_img');
        $this->dropColumn('{{%types}}','expires');
        $this->dropColumn('{{%productions}}','expires');
        $this->dropColumn('{{%schema_productions}}','expires');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170919_193735_add_image cannot be reverted.\n";

        return false;
    }
    */
}
