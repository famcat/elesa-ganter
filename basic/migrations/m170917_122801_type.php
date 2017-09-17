<?php

use yii\db\Schema;
use yii\db\Migration;

class m170917_122801_type extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%types}}',[
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'url' => $this->string(255)->notNull()
        ]);
    }

    public function safeDown()
    {
        echo "m170917_122801_type cannot be reverted.\n";
        $this->dropTable('{{%types}}');
    }
}
