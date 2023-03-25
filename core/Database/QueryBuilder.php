<?php

namespace Core\Database;

use Exception;

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
        $values = implode(', ', array_map(fn ($value) => ":{$value}", array_keys($values)));
        return "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ");";
    }


    public static function select(string $table, array $columns = ['*']): string
    {
        $implodedColumns = implode(', ', $columns);

        return "SELECT " . $implodedColumns . " FROM " . $table . ";";
    }

    public static function where(string $column, string $operator, string $value): string
    {
        return "WHERE " . $column . " " . $operator . " " . $value . ";";
    }

    /**
     * @throws Exception
     */
    public static function selectWhere(string $table, array $clause, array $columns = ['*']): string
    {
        $select = self::select($table, $columns);
        $select = str_replace(';', '', $select);

        if (count($clause) < 3) {
           throw new Exception('Invalid clause');
        }

        $where = self::where("{$table}.{$clause[0]}", $clause[1], $clause[2]);

        return $select . ' ' . $where;
    }
}