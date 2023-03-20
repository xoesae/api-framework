<?php

use App\Utils\Env;

# Register autoload
require __DIR__ . '/../../vendor/autoload.php';

# Set environment variables
Env::set();

# Run application
(new \App\Routes\Router());