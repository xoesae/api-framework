<?php

# Register autoload
require __DIR__ . '/../../vendor/autoload.php';

# Run application
$router = new \App\Routes\Router();

$router->get('/teste', 'TesteController@index');