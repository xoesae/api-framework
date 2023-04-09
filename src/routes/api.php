<?php

use Core\Routes\Router;

// User Routes
Router::get('/users', 'UserController@index');
Router::post('/user', 'UserController@store');
Router::get('/user/:id', 'UserController@show');
Router::put('/user/:id', 'UserController@update');
Router::delete('/user/:id', 'UserController@delete');
