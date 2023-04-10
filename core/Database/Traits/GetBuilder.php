<?php

namespace Core\Database\Traits;

trait GetBuilder
{
    public function get(): bool|array
    {
        $sql = "SELECT {$this->select} FROM {$this->table}{$this->where}";
        $statement = self::pdo()->prepare($sql);
        $statement->execute($this->params);

        return $statement->fetchAll();
    }
}