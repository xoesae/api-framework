<?php

$this->get('/users', 'UserController@index');
$this->post('/user', 'UserController@store');
$this->get('/user/:id', 'UserController@show');