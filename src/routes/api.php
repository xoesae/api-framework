<?php

use App\Controllers\UserController;
use Core\Routes\Router;

Router::group([
    'controller' => UserController::class,
    'prefix' => '/api',
], function () {
    Router::get('/users', 'index');
    Router::post('/user', 'store');
    Router::get('/user/:id', 'show');
    Router::put('/user/:id', 'update');
    Router::delete('/user/:id', 'delete');
});