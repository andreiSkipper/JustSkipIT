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
            'location' => $this->string(255),
            'ip' => $this->string(16),
            'status' => $this->boolean(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('comments');
    }
}
