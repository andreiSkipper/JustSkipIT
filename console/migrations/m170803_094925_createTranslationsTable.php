<?php

use yii\db\Migration;

class m170803_094925_createTranslationsTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%translations}}', [
            'id' => $this->primaryKey(),
            'category' => $this->string(255),
            'message' => $this->text()->notNull(),
            'en' => $this->text()->notNull(),
            'ro' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addColumn('user', 'language', $this->string(5)->defaultValue('en-US'));
    }

    public function safeDown()
    {
        $this->dropTable('translations');
        $this->dropColumn('user', 'language');
    }
}
