<?php namespace Core\Database;

use Exception;
use Core\Database\PDOAdapter;

class MySqlConnection
{
    private static $connection = null;

    public static function connect(array $dbdata)
    {
        try {
            if(self::$connection == null) {
                self::$connection = new PDOAdapter(
                    $dbdata['driver'],
                    $dbdata['host'],
                    $dbdata['database'],
                    $dbdata['user'],
                    $dbdata['password']
                );
            };

            return self::$connection;
        }
        catch (Exception $e) {
            die(sprintf("ERROR 500 - Database connection error\n%s::(%d)\n", $e->getMessage(), $e->getCode()));
        }
    }

    public function getConn() 
    {
        return self::connect()->getPdo();
    }
}