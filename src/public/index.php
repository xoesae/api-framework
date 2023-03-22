<?php

header('Content-Type: application/json; charset=utf-8');

use Core\Routes\Router;
use Core\Utils\Env;

# Register autoload
require __DIR__ . '/../../vendor/autoload.php';

# Set environment variables
Env::set();

# Run application
(new Router());