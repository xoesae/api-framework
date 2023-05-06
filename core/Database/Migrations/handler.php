<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Core\Database\Migrations\Migration;
use Core\Utils\Env;

# Set environment variables
Env::set();

$handler = new Migration();
$handler->run();