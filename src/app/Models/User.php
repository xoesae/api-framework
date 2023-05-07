<?php

namespace App\Models;

use Core\Models\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property $created_at
 * @property $updated_at
 */
class User extends Model
{
    protected array $hidden = ['password'];
}