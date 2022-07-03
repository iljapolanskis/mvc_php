<?php

namespace MVC\Core\Models\Abstract;

use MVC\Core\Application;
use PDOStatement;

abstract class DbModel extends Model
{
    abstract public function table(): string;

    abstract public function attributes(): array;

    public function columns(): string
    {
        return implode(',', $this->attributes());
    }

    public function values()
    {
        return implode(',', array_map(fn($attr) => ":$attr", $this->attributes()));
    }

    public function load() {}

    public function save(): bool
    {
        $sql = "
            INSERT INTO {$this->table()}
            ({$this->columns()})
            VALUES
            ({$this->values()})";
        $stmt = $this->prepare($sql);

        foreach ($this->attributes() as $attribute) {
            $stmt->bindValue(":$attribute", $this->$attribute);
        }

        return $stmt->execute();
    }

    public function prepare(string $sql): PDOStatement|false
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}