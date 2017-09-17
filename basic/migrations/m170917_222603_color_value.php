<?php

use yii\db\Migration;

class m170917_222603_color_value extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%color_value}}',[
            'id' => $this->primaryKey(),
            'color_list_id' => $this->integer(),
            'color_field_id' => $this->integer(),
            'color_value' => $this->string(255)
        ]);

        $this->createIndex(
            'idx-color_value-color_list_id',
            'color_value',
            'color_list_id'
        );

        $this->createIndex(
            'idx-color_value-color_field_id',
            'color_value',
            'color_field_id'
        );

        $this->addForeignKey(
            'fk-color_value-color_list_id',
            'color_value',
            'color_list_id',
            'color_list',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-color_value-color_field_id',
            'color_value',
            'color_field_id',
            'color_field',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        echo "m170917_222603_color_value cannot be reverted.\n";
        $this->dropTable('{{%color_value}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170917_222603_color_value cannot be reverted.\n";

        return false;
    }
    */
}
