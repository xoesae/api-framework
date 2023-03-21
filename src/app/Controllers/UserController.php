<?php

namespace App\Controllers;

use App\Requests\Request;
use App\Models\BaseModel;
use App\Routes\Response;

class UserController
{
    public function __construct(
        public Request $request = new Request(),
    ) {}

    public function index()
    {
        http_response_code(200);

        $users = BaseModel::all();

        Response::json($users);
    }

    public function store()
    {
        echo '<br> Hello Post! <br>';
    }
}