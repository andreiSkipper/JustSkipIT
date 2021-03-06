<?php

use yii\db\Migration;

class m170811_123945_createCommentsTable extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%comments}}', [
            'id' => $this->primaryKey(),
            'reply_id' => $this->integer(11),
            'user_id' => $this->integer(11),
            'action_id' => $this->integer(11),
            'content' => $this->text()->notNull(),
            'location' => $this->text(),
            'ip' => $this->string(16),
            'status' => $this->boolean(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('comments');
    }
}
