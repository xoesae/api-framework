<?php

namespace Core\Database\Traits;

use PDOStatement;

trait UpdateBuilder
{
    private bool $isUpdating = false;
    private string $updateColumnsSet = '';
    private array $updateValues = [];

    public function update(array $data): static
    {
        $formattedSet = [];

        foreach ($data as $key => $value) {
            $formattedSet[] = $key . " = :{$key}";
            $this->params[$key] = $value;
        }

        $this->updateColumnsSet = implode(', ', $formattedSet);
        $this->updateValues = $data;
        $this->isUpdating = true;

        return $this;
    }

    private function updateHandler(): string
    {
        return "UPDATE {$this->table} SET {$this->updateColumnsSet}{$this->where}";
    }
}