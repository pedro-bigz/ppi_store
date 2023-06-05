<?php namespace Core\Database;

class PDOAdapter
{
    private function __construct(string $host, string $database, string $user, string $password)
    {
        $this->pdo = new PDOAdapter("mysql:host=" . $host . ";dbname=" . $database, $user, $password);
    }

    public function getPdo() 
    {
        return $this->pdo;
    }
}