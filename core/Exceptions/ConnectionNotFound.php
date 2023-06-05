<?php namespace Core\Exceptions;

use Exception;

class ConnectionNotFound extends Exception
{
    public static function create($message)
    {
        return new static(sprintf(array_shift($message), ...$message), 500);
    }

    public function abort()
    {
        die($this->message);
    }
}