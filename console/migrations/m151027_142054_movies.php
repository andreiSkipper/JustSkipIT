<?php

use yii\db\Schema;
use yii\db\Migration;

class m151027_142054_movies extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `movies` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `duration` int(11) NOT NULL,
                  `year` int(4) NOT NULL,
                  `genre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `director` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `release_date` date NOT NULL,
                  `stars` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `video` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `plot` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";

        $this->execute($sql);
    }

    public function down()
    {
        $this->dropTable('{{%movies}}');
    }
}
