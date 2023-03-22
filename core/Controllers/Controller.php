<?php

namespace Core\Controllers;

use Core\Requests\Request;

class Controller
{
    public function __construct(
        public Request $request = new Request(),
    ) {}
}