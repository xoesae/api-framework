<?php

namespace Core\Database\Traits;

trait InsertBuilder
{
    private bool $isInserting = false;
    private array $insertColumns = [];
    private array $insertValues = [];

    public function insert(array $data): static
    {
        $this->insertColumns = array_keys($data);
        $this->insertValues = array_values($data);
        $this->isInserting = true;

        return $this;
    }

    private function insertHandler(): string
    {
        $insertColumns = implode(', ', $this->insertColumns);
        $placeholders = implode(', ', array_fill(0, count($this->insertValues), '?'));

        $this->params = $this->insertValues;
        return "INSERT INTO {$this->table} ({$insertColumns}) VALUES ({$placeholders})";
    }
}