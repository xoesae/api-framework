<?php

namespace App\Controllers;

use App\Models\User;
use Core\Controllers\Controller;
use Core\Routes\Response;
use Core\Requests\Request;
use Core\Utils\Hash;

class UserController extends Controller
{
    public function __construct(
        private User $user = new User(),
    ) {}

    public function index()
    {
        $users = $this->user->all();

        Response::json($users);
    }

    public function store()
    {
        $data = (new Request())->all();

        $data['password'] = Hash::make($data['password']);
        
        $this->user->create($data);
        
        $users = $this->user->all();
        
        Response::json($users);
    }
}