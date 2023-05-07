<?php

namespace Core\Database\Factories;

use Core\Models\Model;

class Factory
{
    protected string $class;

    public function definition(): array
    {
        return [];
    }

    public function create(): void
    {
       $model = new $this->class();

       $model->create($this->definition());
    }
}