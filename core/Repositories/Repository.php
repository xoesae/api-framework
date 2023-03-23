<?php

namespace Core\Repositories;

class Repository
{
    private string $table;

    public function __construct(
        private $model,
    ) {
        $this->table = $model->getTable();
    }

    // TODO: Implement methods
}