<?php

use yii\db\Migration;

class m170917_152147_production extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%productions}}',[
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'materail' => $this->string(255),
            'url' => $this->string(255),
            'full_description' => $this->text(),
            'img_url' => $this->string(255),
            'types_id' => $this->integer()->notNull()
        ]);
        $this->createIndex(
          'idx-productions-types_id',
          'productions',
           'types_id'
        );
        $this->addForeignKey(
            'fk-productions-types_id',
            'productions',
            'types_id',
            'types',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        echo "m170917_152147_production cannot be reverted.\n";
        $this->dropTable('{{%productions}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170917_152147_production cannot be reverted.\n";

        return false;
    }
    */
}
