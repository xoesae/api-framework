<?php

namespace Factories;

use App\Models\User;
use Core\Database\Factories\Factory;
use Core\Utils\Hash;

class UserFactory extends Factory
{
    protected string $class = User::class;

    public function definition(): array
    {
        return [
            'name' => 'John Doe',
            'email' => 'johndoe@email.com',
            'password' => Hash::make('password'),
        ];
    }
}