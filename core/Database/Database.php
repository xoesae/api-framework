<?php

namespace Core\Database;

use Exception;
use PDOException;

class Database extends Connect
{
    public static function tableExists(string $table): bool
    {
        $result = false;

        try {
            $pdo = self::pdo();
            if (is_null($pdo)) {
                return false;
            }

            $query = "SELECT 1 FROM {$table} LIMIT 1";
            $result = $pdo->query($query);
            
        } catch (PDOException $e) {
            echo $e->getMessage();
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

            $query = QueryBuilder::createTable($table, $columns);
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

            foreach ($values as $key => $value) {
                $statement->bindValue(":{$key}", $value);
            }

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

    public static function find(int $id, string $table, array $columns = ['*'])
    {
        $result = [];

        try {
            $pdo = self::pdo();
            if (is_null($pdo)) {
                return;
            }

            $clause = [
                'id', '=', $id
            ];

            $query = QueryBuilder::selectWhere($table, $clause, $columns);

            $statement = $pdo->prepare($query);
            $statement->execute(); 

            $result = $statement->fetch();
        } catch (PDOException|Exception $e) {
            echo $e->getMessage();
        }

        return $result;
    }
}