<?php

namespace App\Database;


use PDOException;

class Database extends Connect
{
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

    public static function createTable(string $table, array $columns)
    {
        try {
            $pdo = self::pdo();
            if (is_null($pdo)) {
                return;
            }

            $query = QueryBuilder::createTable();
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

    public static function insert(string $table, array $values = [])
    {
        try {
            $pdo = self::pdo();
            if (is_null($pdo)) {
                return;
            }

            $query = QueryBuilder::insert($table, $values);
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

    public static function select(string $table, $columns = ['*'])
    {
        $result = [];

        try {
            $pdo = self::pdo();
            if (is_null($pdo)) {
                return;
            }

            $query = QueryBuilder::select($table, $columns);
            $statement = $pdo->prepare($query);
            $statement->execute(); 

            $result = $statement->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $result;
    }

    public function query(string $query, $data = [])
    {
        $result = [];

        try {
            $pdo = self::pdo();
            if (is_null($pdo)) {
                return;
            }

            $pdo->beginTransaction();
            $statement = $pdo->prepare($query);

            $executed = $statement->execute($data);
            $rowCount = $statement->rowCount();

            if ($executed && $rowCount > 0) {
                $pdo->commit();
                $result = $statement;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $result;
    }
}