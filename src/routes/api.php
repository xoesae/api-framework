<?php

$this->get('/users', 'UserController@index');
$this->post('/user', 'UserController@store');