<?php

use yii\db\Migration;

class m171018_113705_document extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%document}}',[
            'id' => $this->primaryKey(),
            'production_id'=> $this->integer()->notNull(),
            'document_title' => $this->string(255)
        ]);

        $this->createIndex(
            'idx-document-production_id',
            'document',
            'production_id'
        );
        $this->addForeignKey(
            'fk-document-production_id',
            'document',
            'production_id',
            'productions',
            'id',
            'CASCADE'
        );

    }

    public function safeDown()
    {
        echo "m171018_113705_document cannot be reverted.\n";
        $this->dropTable('{{%document}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171018_113705_document cannot be reverted.\n";

        return false;
    }
    */
}
