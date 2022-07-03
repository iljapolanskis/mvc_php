<?php

namespace MVC\Core;

use MVC\Core\Database\Interfaces\Migration;
use PDO;
use PDOStatement;

class Database
{
    public PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $this->dsn($config);
        $user = $config['user'];
        $pass = $config['pass'];

        $this->pdo = new PDO($dsn, $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function dsn(array $config): string
    {
        return "mysql:host={$config['host']};port={$config['port']};dbname={$config['name']}";
    }

    public function applyMigrations()
    {
        $this->createMigrationsTablae();
        $applied = $this->getAppliedMigrations();

        $added = [];
        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApply = array_diff($files, $applied);


        foreach ($toApply as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once Application::$ROOT_DIR . "/migrations/$migration";
            $className = pathinfo($migration, PATHINFO_FILENAME);

            /* @var $migration Migration */
            $instance = new $className();

            $this->log("Applying migration {$migration}...");
            $instance->up();
            $this->log("Migration {$migration} applied");

            $added[] = $migration;
        }

        if (!empty($added)) {
            $this->saveMigrations($added);
        } else {
            $this->log("No migrations to apply");
        }
    }

    private function saveMigrations(array $added)
    {
        $values = implode(",", array_map(fn($migration) => "('$migration')", $added));
        $sql = "INSERT INTO migrations (migration) VALUES $values";
        $this->pdo->prepare($sql)->execute();
    }

    public function createMigrationsTablae()
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    public function getAppliedMigrations(): array
    {
        $sql = "SELECT migration FROM migrations";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function prepare($sql): PDOStatement|false
    {
        return $this->pdo->prepare($sql);
    }

    private function log(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        echo "[$date] $message".PHP_EOL;
    }
}