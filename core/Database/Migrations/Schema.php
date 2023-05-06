<?php

namespace Core\Database\Migrations;

class Schema
{
    public array $columns = [];

    private function addColumn(string $column, string $type): void
    {
        $this->columns[$column] = $type;
    }

    public function increments(string $column): void
    {
        $this->addColumn($column, "INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY");
    }

    public function string(string $column, $size = 255): void
    {
        $this->addColumn($column, "VARCHAR({$size})");
    }

    // TODO: add unique, nullable, default, foreign key, etc...
}