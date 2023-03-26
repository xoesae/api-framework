<?php

namespace App\Controllers;

use App\Models\User;
use Core\Controllers\Controller;
use Core\Database\Database;
use Core\Routes\Response;
use Core\Requests\CustomRequest;
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
        $data = (new CustomRequest())->all(); // TODO: fix this

        $data['password'] = Hash::make($data['password']);
        
        $this->user->create($data);
        
        $users = $this->user->all();
        
        Response::json($users);
    }

    public function update(int $id)
    {
        $data = (new CustomRequest())->all(); // TODO: fix this

        $data['password'] = Hash::make($data['password']);
        
        $this->user->update($id, $data);
        
        $user = $this->user->find($id);
        
        Response::json($user);
    }

    public function delete(int $id)
    {
        $deleted = $this->user->delete($id);

        Response::json($deleted);
    }
}