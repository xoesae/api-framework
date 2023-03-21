<?php

namespace App\Models;

use App\Database\Database;

class BaseModel
{
    public static array $protectedVars = ['table'];
    public static string $table = 'base';
    public string $id = 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
    public string $created_at = 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP';
    public string $updated_at = 'DATETIME NULL ON UPDATE CURRENT_TIMESTAMP';

    public static function getColumns(): array
    {
        $columns = get_class_vars(get_class());
        unset($columns['protectedVars']);

        foreach ($columns as $key => $column) {
            if (in_array($key, self::$protectedVars)) {
                unset($columns[$key]);
            }
        }
        
        return $columns;
    }

    public static function all()
    {
        $exists = Database::tableExists(self::$table);
        if (!$exists) {
            Database::createTable(self::$table, self::getColumns());
        }

        return Database::select(self::$table);
    }

    public static function create(array $values = [])
    {
        $exists = Database::tableExists(self::$table);
        if (!$exists) {
            Database::createTable(self::$table, self::getColumns());
        }

        $values = [
            ...$values,
            'id' => 'DEFAULT',
            'created_at' => 'DEFAULT',
        ];

        Database::insert(self::$table, $values);
    }

}