<?php

namespace Core\Database\Migrations;

use Closure;
use Core\Database\Database;

class Migration
{
    const MIGRATIONS_DIR = __DIR__ . '/../../../src/database/migrations';

    public static array $tables = [];

    public function __construct()
    {
        self::loadMigrations();
    }

    private static function getFullMigrationDir(string $migration): string
    {
        return self::MIGRATIONS_DIR . '/' . $migration;
    }

    private static function loadMigrations(): void
    {
        $migrations = scandir(self::MIGRATIONS_DIR);

        // Remove . and .. from array
        $migrations = array_diff($migrations, ['.', '..']);

        foreach ($migrations as $migration) {
            require self::getFullMigrationDir($migration);
        }
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

        echo PHP_EOL . 'Migrations ran successfully!' . PHP_EOL;
    }
}