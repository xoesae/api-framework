<?php

namespace App\Database;

use App\Utils\Env;
use PDO;
use PDOException;

class Database
{
    public static $DB_HOST;
    public static $DB_PORT;
    public static $DB_NAME;
    public static $DB_USER;
    public static $DB_PASSWORD;
    public static $BD_DRIVER = 'mysql';
    public static $DB_CHARSET = 'UTF8';
    public static $DB_COLLATE = 'utf8_general_ci';
    public static ?PDO $pdo = null;

    private static function getConnectionString(): string
    {
        return "mysql:host=" . self::$DB_HOST . 
            ";port=" . self::$DB_PORT .
            ";dbname=" . self::$DB_NAME . 
            ";charset=" . self::$DB_CHARSET;
            ";";
    }

    private static function connect(): PDO
    {
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_FOUND_ROWS => true,
            ];
            
            $pdo = new PDO(
                self::getConnectionString(), 
                self::$DB_USER, 
                self::$DB_PASSWORD === "" ? null : self::$DB_PASSWORD, 
                $options
            );
            $pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
            $pdo->setAttribute(PDO::ATTR_TIMEOUT, 300000);

            return $pdo;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function create()
    {
        if (!is_null(self::$pdo)) {
            self::$pdo->query("
                CREATE DATABASE IF NOT EXISTS " . self::$DB_NAME . 
                " DEFAULT CHARACTER SET " . self::$DB_CHARSET . 
                " COLLATE " . self::$DB_COLLATE . 
                ";USE " . self::$DB_NAME .
                "; COMMIT
            ");
        }
    }

    public static function pdo()
    {
        self::$DB_HOST = Env::get('DB_HOST', '127.0.0.1');
        self::$DB_PORT = Env::get('DB_PORT', '3306');
        self::$DB_NAME = Env::get('DB_NAME', 'test');
        self::$DB_USER = Env::get('DB_USER', 'root');
        self::$DB_PASSWORD = Env::get('DB_PASSWORD', null);
        self::$DB_CHARSET = Env::get('DB_CHARSET', 'UTF8');

        if (is_null(self::$pdo)) {
            self::$pdo = self::connect();
        }

        self::create();

        return self::$pdo;
    }

    # SQL

    public static function tableExists(string $table): bool
    {
        $result = false;

        try {
            $pdo = self::pdo();
            if (is_null($pdo)) {
                return $result;
            }

            $query = "SELECT 1 FROM {$table} LIMIT 1";
            $result = $pdo->query($query);
            
        } catch (PDOException $e) {
            return false;
        }

        return $result !== false;
    }

    public static function makeCreateTableQuery(string $table, array $columns): string
    {
        $formattedColumns = [];
        foreach ($columns as $column => $type) {
            $formattedColumns[] = $column . ' ' . $type;
        }

        $sql = "CREATE TABLE IF NOT EXISTS " . $table . " (";
        $sql .= implode(', ', $formattedColumns);
        $sql .= ");";

        return $sql;
    }

    public static function createTable(string $table, array $columns)
    {
        try {
            $pdo = self::pdo();
            if (is_null($pdo)) {
                return;
            }

            $query = self::makeCreateTableQuery($table, $columns);
            $pdo->beginTransaction();
            echo 'QUERY: ' . $query . '<br>';
            $statement = $pdo->prepare($query);

            $executed = $statement->execute();
            $rowCount = count($statement->rowCount());

            if ($executed && $rowCount > 0) {
                $pdo->commit();
            }
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function makeInsertQuery(string $table, array $values = []): string
    {
        $columns = implode(', ', array_keys($values));
        $values = implode(', ', array_values($values));
        $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ");";

        return $sql;
    }

    public static function insert(string $table, array $values = [])
    {
        try {
            $pdo = self::pdo();
            if (is_null($pdo)) {
                return;
            }

            $query = self::makeInsertQuery($table, $values);
            $pdo->beginTransaction();
            $statement = $pdo->prepare($query);

            $executed = $statement->execute();
            $rowCount = $statement->rowCount();

            if ($executed && $rowCount > 0) {
                $pdo->commit();
            }
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}