<?php

use MVC\Core\Application;
use MVC\Core\Database\Interfaces\Migration;

class m0002_users_add_password implements Migration
{
    public function up()
    {
        $db = Application::$app->db;
        $db->pdo->exec("ALTER TABLE users ADD COLUMN password VARCHAR(255) NOT NULL");
    }

    public function down()
    {
        $db = Application::$app->db;
        $db->pdo->exec("ALTER TABLE users DROP COLUMN password");
    }
}