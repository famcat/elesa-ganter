<?php

use yii\db\Migration;

class m170917_181735_schema extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%schema_productions}}',[
           'id' => $this->primaryKey(),
            'name_schema' => $this->text(255),
            'production_id' => $this->integer()->notNull(),
            'schema_id' => $this->string(255),
            'img_production_url' => $this->string(255)
        ]);
        $this->createIndex(
            'idx-schema_productions-production_id',
            'schema_productions',
            'production_id'
        );
        $this->addForeignKey(
            'fk-schema_productions-production_id',
            'schema_productions',
            'production_id',
            'productions',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        echo "m170917_181735_schema cannot be reverted.\n";
        $this->dropTable('{{%schema_productions}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170917_181735_schema cannot be reverted.\n";

        return false;
    }
    */
}
