<?php

namespace Core\Database\Traits;

trait DeleteBuilder
{
    private bool $isDeleting = false;

    public function delete(): static
    {
        $this->isDeleting = true;

        return $this;
    }

    private function deleteHandler(): string
    {
        return "DELETE FROM {$this->table}{$this->where}";
    }
}