<?php namespace App\Exceptions;

use Exception;

class IniSizeFileException extends Exception {
    public static function create($message)
    {
        return new static(sprintf(array_shift($message), ...$message), 400);
    }

    public function abort()
    {
        die;
    }
}