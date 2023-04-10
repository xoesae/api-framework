<?php

namespace Core\Database;

use Core\Database\Traits\DeleteBuilder;
use Core\Database\Traits\GetBuilder;
use Core\Database\Traits\InsertBuilder;
use Core\Database\Traits\UpdateBuilder;

class QueryBuilder extends Connect
{
    use GetBuilder, InsertBuilder, UpdateBuilder, DeleteBuilder;

    private string $table;
    private string $select = '*';
    private string $where = '';
    private array $params = [];

    public function __construct(string $table) {
        $this->table = $table;
    }

    public function select(string $columns): static
    {
        $this->select = $columns;
        return $this;
    }

    public function where(string $conditions, array $params): static
    {
        $this->where = " WHERE {$conditions}";
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    public function execute(): bool
    {
        $result = false;
        $pdo = self::pdo();
        $sql = "";

        if ($this->isInserting) {
           $sql = $this->insertHandler();
        }

        if ($this->isUpdating) {
            $sql = $this->updateHandler();
        }

        if ($this->isDeleting) {
            $sql = $this->deleteHandler();
        }

        $pdo->beginTransaction();
        $statement = $pdo->prepare($sql);

        $executed = $statement->execute($this->params);
        $rowCount = $statement->rowCount();

        if ($executed && $rowCount > 0) {
            $pdo->commit();
            $result = true;
        }

        $this->isInserting = false;
        $this->isUpdating = false;
        $this->isDeleting = false;

        return $result;
    }
}