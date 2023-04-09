<?php

use App\Controllers\UserController;
use Core\Routes\Router;

// User Routes
//Router::controller(UserController::class, function () {
//    Router::get('/users', 'index');
//    Router::post('/user', 'store');
//    Router::get('/user/:id', 'show');
//    Router::put('/user/:id', 'update');
//    Router::delete('/user/:id', 'delete');
//});

Router::get('/test', 'UserController@index');

Router::group([
    'controller' => UserController::class,
], function () {
    Router::get('/users', 'index');
    Router::post('/user', 'store');
    Router::get('/user/:id', 'show');
    Router::put('/user/:id', 'update');
    Router::delete('/user/:id', 'delete');
});