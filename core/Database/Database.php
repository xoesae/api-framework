<?php

namespace Core\Database;

use Core\Database\Traits\CreateTableBuilder;

class Database extends Connect
{
    use CreateTableBuilder;

    public static function tableExists(string $table): bool
    {
        $result = false;

        $pdo = self::pdo();
        $sql = "SELECT 1 FROM {$table} LIMIT 1";
        $result = $pdo->query($sql);

        return $result !== false;
    }

    public static function createTable(string $table, array $columns): bool
    {
        $result = false;

        $pdo = self::pdo();

        $sql = self::createTableBuilder($table, $columns);

        $pdo->beginTransaction();
        $statement = $pdo->prepare($sql);

        $executed = $statement->execute();
        $rowCount = $statement->rowCount();

        if ($executed && $rowCount > 0) {
            $pdo->commit();
            $result = true;
        }

        return $result;
    }
}