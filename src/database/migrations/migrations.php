<?php

use Core\Database\Migrations\Migration;
use Core\Database\Migrations\Schema;

Migration::create('users', function (Schema $schema) {
    $schema->increments('id');
    $schema->string('name');
    $schema->string('email');
    $schema->string('password');
    $schema->string('created_at');
    $schema->string('updated_at');
});

Migration::create('companies', function (Schema $schema) {
    $schema->increments('id');
    $schema->string('name');
});
