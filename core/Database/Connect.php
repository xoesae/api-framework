<?php

namespace Core\Database;

use Core\Utils\Env;
use PDO;
use PDOException;

class Connect
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
        return self::$BD_DRIVER . 
            ":host=" . self::$DB_HOST . 
            ";port=" . self::$DB_PORT .
            ";dbname=" . self::$DB_NAME . 
            ";charset=" . self::$DB_CHARSET;
    }

    protected static function connect(): PDO
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
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    private static function create(): void
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

    protected static function pdo(): PDO
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
}