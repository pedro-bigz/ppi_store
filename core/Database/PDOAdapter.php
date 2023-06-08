<?php namespace Core\Database;

use PDO;

class PDOAdapter
{
    private PDO $pdo;

    public function __construct(string $driver, string $host, string $database, string $user, string $password)
    {
        try {
            $this->pdo = new PDO("{$driver}:host={$host};dbname={$database}", $user, $password);
        }
        catch (Exception $e) {
            echo $e->getMessage();
            die("ERROR 500 - Database connection error");
        }
    }

    public function getPdo() 
    {
        return $this->pdo;
    }
}