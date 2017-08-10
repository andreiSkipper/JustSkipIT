<?php

use yii\db\Schema;
use yii\db\Migration;

class m160430_185856_createActionsTable extends Migration
{
    public function up()
    {
        $this->createTable('{{%actions}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'type' => $this->string(255)->notNull(),
            'imagePath' => $this->string(255),
            'description' => $this->string(255),
            'location' => $this->string(255),
            'privacy' => $this->string(255),
            'fealing' => $this->string(255),
            'tags' => $this->string(255),
            'likes' => 'longtext NULL',
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('actions');
    }
}
