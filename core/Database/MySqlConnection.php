<?php namespace Core\Database;

use Exception;

class MySqlConnection
{
    private static $connection = null;

    private static function connect(array $dbdata)
    {
        try {
            if(self::$connection == null) {
                self::$connection = new PDOAdapter($dbdata['host'], $dbdata['database'], $dbdata['user'], $dbdata['pass']);
            };
        }
        catch (Exception $e) {
            echo $e->getMessage();
            die("ERROR 500 - Database connection error");
        } finally {
            return self::$connection;
        }
    }

    public function getConn() 
    {
        return self::connect()->getPdo();
    }
}