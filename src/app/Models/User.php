<?php

namespace App\Models;

use Core\Models\Model;

class User extends Model
{
    public string $name = 'VARCHAR(255) NOT NULL';
    public string $email = 'VARCHAR(255) NOT NULL';
    public string $password = 'VARCHAR(255) NOT NULL';
}