<?php namespace App\Exceptions;

use Exception;

class ExtensionFileException extends Exception {
    public static function create($message)
    {
        return new static(sprintf(array_shift($message), ...$message), 400);
    }

    public function abort()
    {
        die;
    }
}