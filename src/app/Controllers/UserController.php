<?php

namespace App\Controllers;

use App\Models\User;
use Core\Controllers\Controller;
use Core\Database\Database;
use Core\Routes\Response;
use Core\Requests\Request;
use Core\Utils\Hash;
use Exception;

class UserController extends Controller
{
    public function __construct(
        private User $user = new User(),
    ) {}

    /**
     * @throws Exception
     */
    public function index()
    {
        $users = $this->user->all();

        Response::json($users);
    }

    /**
     * @throws Exception
     */
    public function show(int $id)
    {
        $user = $this->user->find($id);

        Response::json($user);
    }

    public function store()
    {
        $data = (new Request())->all(); // TODO: fix this

        $data['password'] = Hash::make($data['password']);
        
        $this->user->create($data);
        
        $users = $this->user->all();
        
        Response::json($users);
    }
}