<?php

$this->get('/users', 'UserController@index');
$this->post('/user', 'UserController@store');
$this->get('/user/:id', 'UserController@show');
$this->put('/user/:id', 'UserController@update');
$this->delete('/user/:id', 'UserController@delete');