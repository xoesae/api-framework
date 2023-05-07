<?php

namespace Core\Models;

use AllowDynamicProperties;
use Core\Database\Database;
use Core\Database\QueryBuilder;
use Core\Routes\Response;
use Exception;

#[AllowDynamicProperties]
class Model
{
    use Traits\TableDoesNotExistsException;

    protected string $table = '';
    protected array $hidden = [];

    protected function cast(array $properties): static
    {
        $object = new static();

        foreach ($properties as $key => $value) {
            $object->$key = $value;
        }

        return $object;
    }

    protected function getTableName(): string
    {
        if ($this->table === '') {
            $class =  get_class($this);
            $class = explode('\\', $class);
            $class = end($class);
            $class = strtolower($class);
            $this->table = $class . 's';
        }

        return $this->table;
    }

    public function all(): array
    {
        $this->validateTableExists();

        $columns = '*';

        $builder = new QueryBuilder($this->getTableName());

        $result = $builder->select($columns)->get();

        if (! is_array($result)) {
            Response::json(["Error while fetching data from {$this->getTableName()}"], 500);
        }

        foreach ($result as $key => $value) {
            $result[$key] = $this->cast($value);
        }

        return $result;
    }

    public function create(array $values = []): void
    {
        $this->validateTableExists();

        $builder = new QueryBuilder($this->getTableName());

        $builder->insert($values)->execute();

        // TODO: return the created object
    }

    public function find(int $id, array $columns = ['*']): static
    {
        $this->validateTableExists();

        $builder = new QueryBuilder($this->getTableName());
        $columns = implode(', ', $columns);

        $result = $builder
            ->select($columns)
            ->where('id = :id',  ['id' => $id])
            ->get();

        if (! is_array($result)) {
            Response::json(["Not found"], 404);
        }

        $response = $result;

        if (count($result) >= 1) {
            $response = (array) $result[0];
        }

        return $this->cast($response);
    }

    public function update(int $id, array $values): bool
    {
        $this->validateTableExists();

        $builder = new QueryBuilder($this->getTableName());

        return $builder
            ->update($values)
            ->where('id = :id',  ['id' => $id])
            ->execute();

        // TODO: return the updated object
    }

    public function delete(int $id): bool
    {
        $this->validateTableExists();

        $builder = new QueryBuilder($this->getTableName());

        return $builder
            ->delete()
            ->where('id = :id',  ['id' => $id])
            ->execute();
    }
}