<?php

namespace Core\Database\Traits;

trait CreateTableBuilder
{
    public static function createTable(string $table, array $columns): string
    {
        $formattedColumns = [];
        foreach ($columns as $column => $type) {
            $formattedColumns[] = $column . ' ' . $type;
        }

        $implodedFormattedColumns = implode(', ', $formattedColumns);

        return "CREATE TABLE IF NOT EXISTS {$table} ({$implodedFormattedColumns});";
    }
}