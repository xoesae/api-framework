<?php

namespace App\Repositories;

class UserRepository extends Repository
{
    public function __construct() 
    {
        parent::__construct(new User);
    }
}