<?php

use yii\db\Migration;

class m170917_214326_color extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%color_list}}',[
           'id' => $this->primaryKey(),
            'production_id'=> $this->integer()->notNull(),
            'color_name' => $this->string(255),
            'color_code' => $this->string(255)
        ]);

        $this->createIndex(
            'idx-color_list-production_id',
            'color_list',
            'production_id'
        );
        $this->addForeignKey(
            'fk-color_list-production_id',
            'color_list',
            'production_id',
            'productions',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%color_field}}',[
            'id' => $this->primaryKey(),
            'production_id'=> $this->integer()->notNull(),
            'name' => $this->string()
        ]);

        $this->createIndex(
            'idx-color_field-production_id',
            'color_field',
            'production_id'
        );
        $this->addForeignKey(
            'fk-color_field-production_id',
            'color_field',
            'production_id',
            'productions',
            'id',
            'CASCADE'
        );


    }

    public function safeDown()
    {
        echo "m170917_214326_color cannot be reverted.\n";
        $this->dropTable('{{%color_list}}');
        $this->dropTable('{{%color_field}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170917_214326_color cannot be reverted.\n";

        return false;
    }
    */
}
