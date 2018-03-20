<?php

use yii\db\Migration;

/**
 * Class m180320_071218_createFriendshipsTable
 */
class m180320_071218_createFriendshipsTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%friendships}}', [
            'id' => $this->primaryKey(),
            'user_from' => $this->integer(11)->notNull(),
            'user_to' => $this->integer(11)->notNull(),
            'status' => "enum('Requested', 'Accepted', 'Refused') default 'Requested'",
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('friendships');
    }
}
