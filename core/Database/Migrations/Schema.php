<?php

namespace Core\Database\Migrations;

class Schema
{
    public array $columns = [];
    public array $properties = [];

    private function addColumn(string $column, string $type): void
    {
        $this->columns[$column] = $type;
    }

    private function addProperty(string $type, string $key): void
    {
        if (isset($this->properties[$type])) {
            $this->properties[$type][] = $key;
            return;
        }

        $this->properties[$type] = [$key];
    }

    public function increments(string $column): void
    {
        $this->addColumn($column, "INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY");
        $this->addProperty('int', $column);
    }

    public function string(string $column, $size = 255): void
    {
        $this->addColumn($column, "VARCHAR({$size})");
        $this->addProperty('string', $column);
    }

    // TODO: add unique, nullable, default, foreign key, etc...
}