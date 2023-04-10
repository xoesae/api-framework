<?php

namespace App\Controllers;

use App\Models\User;
use Core\Controllers\Controller;
use Core\Database\QueryBuilder;
use Core\Routes\Response;
use Core\Requests\FormRequest;
use Core\Utils\Hash;
use Exception;

class UserController extends Controller
{
    public function __construct(
        public User $user,
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
    public function show(FormRequest $request, int $id)
    {
        $user = $this->user->find($id);

        Response::json($user);
    }

    public function store(FormRequest $request)
    {
        $data = $request->all();

        $data['password'] = Hash::make($data['password']);
        
        $this->user->create($data);
        
        $users = $this->user->all();
        
        Response::json($users);
    }

    /**
     * @throws Exception
     */
    public function update(FormRequest $request, int $id)
    {
        $data = $request->all();
        
        $data['password'] = Hash::make($data['password']);
        
        $this->user->update($id, $data);

        $user = $this->user->find($id);
        
        Response::json($user);
    }

    /**
     * @throws Exception
     */
    public function delete(int $id)
    {
        $deleted = $this->user->delete($id);

        Response::json($deleted);
    }
}