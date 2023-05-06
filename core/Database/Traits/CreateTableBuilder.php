<?php

namespace Core\Database\Traits;

trait CreateTableBuilder
{
    private static function createTableBuilder(string $table, array $columns): string
    {
        $formattedColumns = [];
        foreach ($columns as $column => $type) {
            $formattedColumns[] = $column . ' ' . $type;
        }

        $implodedFormattedColumns = implode(', ', $formattedColumns);

        return "CREATE TABLE {$table} ({$implodedFormattedColumns});";
    }
}