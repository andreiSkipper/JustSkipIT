<?php

use yii\db\Migration;

/**
 * Class m180526_082013_createNotificationsTable
 */
class m180526_082013_createNotificationsTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notifications}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'model' => $this->text(),
            'type' => $this->string(30),
            'status' => "enum('Added', 'Removed', 'Pending') default 'Added'",
            'read' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('notifications');
    }
}
