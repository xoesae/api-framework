<?php

namespace Core\Models\Traits;

use Core\Database\Database;

trait TableDoesNotExistsException
{
    protected function validateTableExists(): void
    {
        $exists = Database::tableExists($this->getTableName());

        if (!$exists) {
            echo "Table {$this->getTableName()} does not exist";
            die();
        }
    }
}