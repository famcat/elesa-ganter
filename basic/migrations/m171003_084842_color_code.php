<?php

use yii\db\Migration;

class m171003_084842_color_code extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%color_code}}',[
            'id' => $this->primaryKey(),
            'color_list_id' => $this->integer()->notNull(),
            'color_article' => $this->string(255)
        ]);

        $this->createIndex('idx-color_code-color_list_id','color_code','color_list_id');
        $this->addForeignKey('fk-color_code-color_list_id','color_code',
            'color_list_id','color_list','id','CASCADE');
    }

    public function safeDown()
    {
        echo "m171003_084842_color_code cannot be reverted.\n";
        $this->dropTable('{{%color_code}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171003_084842_color_code cannot be reverted.\n";

        return false;
    }
    */
}
