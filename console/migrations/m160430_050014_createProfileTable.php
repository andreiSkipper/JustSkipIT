<?php

use yii\db\Schema;
use yii\db\Migration;

class m160430_050014_createProfileTable extends Migration
{
    public function up()
    {
        $this->createTable('{{%profiles}}', [
            'user_id' => $this->primaryKey(),
            'firstname' => $this->string(255)->notNull(),
            'lastname' => $this->string(255)->notNull(),
            'nickname' => $this->string(255),
            'avatar' => $this->string(255),
            'cover' => $this->string(255),
            'address' => $this->string(255),
            'phoneNumber' => $this->string(255),
            'currentCity' => $this->string(255),
            'birthCity' => $this->string(255),
            'work' => $this->string(255),
            'birthday' => $this->date(),
            'sex' => $this->string(255),
            'interestedIn' => $this->string(255),
            'knownLanguages' => $this->string(255),
            'relationship' => $this->string(255),
            'description' => $this->string(255),
            'shortUrl' => $this->string(20),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function down()
    {
        $this->dropTable('profiles');
    }
}
