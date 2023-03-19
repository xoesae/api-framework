<?php

namespace App\Controllers;

use App\Requests\Request;

class UserController
{
    public function __construct(
        public Request $request = new Request(),
    ) {}

    public function index()
    {
        echo '<br> Hello World! <br>';
    }

    public function store()
    {
        echo '<br> Hello Post! <br>';
    }
}