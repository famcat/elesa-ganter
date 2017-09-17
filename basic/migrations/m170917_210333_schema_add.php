<?php

use yii\db\Migration;

class m170917_210333_schema_add extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%schema_productions}}','img_schema',$this->string(255));
    }

    public function safeDown()
    {
        echo "m170917_210333_schema_add cannot be reverted.\n";
        $this->dropColumn('{{%schema_productions}}','img_schema');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170917_210333_schema_add cannot be reverted.\n";

        return false;
    }
    */
}
