<?php

use MVC\Core\Application;
use MVC\Core\Database\Interfaces\Migration;

class m0001_create_users_table implements Migration
{
    public function up()
    {
        $db = Application::$app->db;
        // TODO: Implement better SQL generation
        $sql = "CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                firstname VARCHAR(255) NOT NULL,
                lastname VARCHAR(255) NOT NULL,
                status TINYINT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )  ENGINE=INNODB;";
        $db->pdo->prepare($sql)->execute();
    }

    public function down()
    {
        $db = Application::$app->db;
        // TODO: Implement better SQL generation
        $sql = "DROP TABLE users;";
        $db->pdo->prepare($sql)->execute();
    }
}