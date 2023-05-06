<?php

namespace Core\Database\Migrations;

use Closure;
use Core\Database\Database;

class Migration
{
    public static array $tables = [];

    public function __construct()
    {
        self::loadMigrations();
    }

    private static function loadMigrations(): void
    {
        require __DIR__ . '/../../../src/database/migrations/migrations.php';
    }

    private static function getSchema(Closure $closure): Schema
    {
        $schema = new Schema();
        $closure($schema);

        return $schema;
    }

    public static function create(string $table, Closure $closure): void
    {
        $schema = self::getSchema($closure);

        self::$tables[$table] = $schema->columns;
    }

    public function run(): void
    {
        foreach (self::$tables as $table => $columns) {
            $exists = Database::tableExists($table);
            if (!$exists) {
                echo 'Creating table: ' . $table . PHP_EOL;
                Database::createTable($table, $columns);
            }
        }

        echo '\nMigrations ran successfully!\n';
    }
}