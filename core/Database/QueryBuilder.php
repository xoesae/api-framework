<?php

namespace Core\Database;

class QueryBuilder
{
    public static function createTable(string $table, array $columns): string
    {
        $formattedColumns = [];
        foreach ($columns as $column => $type) {
            $formattedColumns[] = $column . ' ' . $type;
        }

        $sql = "CREATE TABLE IF NOT EXISTS " . $table . " (";
        $sql .= implode(', ', $formattedColumns);
        $sql .= ");";

        return $sql;
    }

    public static function insert(string $table, array $values = []): string
    {
        $columns = implode(', ', array_keys($values));
        $values = implode(', ', array_map(fn () => '?', $values));
        $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ");";

        return $sql;
    }


    public static function select(string $table, array $columns = ['*']): string
    {
        $implodedColumns = implode(', ', $columns);

        $sql = "SELECT " . $implodedColumns . " FROM " . $table . ";";

        return $sql;
    }
}