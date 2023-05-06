<?php

require __DIR__ . '/../../vendor/autoload.php';

use Core\Stubs\Migration\CreateNewMigration;

$args = $argv;

# remove the name of the script
array_shift($args);

$typeOfMake = array_shift($args);

if (is_null($typeOfMake)) {
    echo "You need to specify the type of make you want to create. Example: make migration\n";
    exit();
}

if ($typeOfMake === 'migration') {
    $table = array_shift($args);

    if (is_null($table)) {
        echo "You need to specify the name of the table. Example: make migration users\n";
        return;
    }

    (new CreateNewMigration())($table);
    echo "Migration created successfully!\n";

    return;
}

echo "Invalid type of make. Example: make migration users\n";


