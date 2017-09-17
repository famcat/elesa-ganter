<?php

use yii\db\Migration;

class m170917_211911_filter extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%filter}}',[
            'id' => $this->primaryKey(),
            'schema_id' => $this->integer(),
            'production_id'=> $this->integer()->notNull(),
            'name' => $this->string()
        ]);


        $this->createIndex(
            'idx-filter-production_id',
            'filter',
            'production_id'
        );
        $this->addForeignKey(
            'fk-filter-production_id',
            'filter',
            'production_id',
            'productions',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%filter_article}}',[
            'id' => $this->primaryKey(),
            'schema_id' => $this->integer(),
            'production_id'=> $this->integer()->notNull(),
            'article_code' => $this->string(255)
        ]);

        $this->createIndex(
            'idx-filter_article-production_id',
            'filter_article',
            'production_id'
        );
        $this->addForeignKey(
            'fk-filter_article-production_id',
            'filter_article',
            'production_id',
            'productions',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%filter_data}}',[
            'id' => $this->primaryKey(),
            'filter_article_id' => $this->integer(),
            'filter_id' => $this->integer(),
            'value' => $this->string(255)
        ]);

    }

    public function safeDown()
    {
        echo "m170917_211911_filter cannot be reverted.\n";
        $this->dropTable('{{%filter}}');
        $this->dropTable('{{%filter_article}}');
        $this->dropTable('{{%filter_data}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170917_211911_filter cannot be reverted.\n";

        return false;
    }
    */
}
