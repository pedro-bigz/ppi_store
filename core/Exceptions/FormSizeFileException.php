<?php namespace Core\Exceptions;

use Exception;

class FormSizeFileException extends Exception
{
    public static function create($message)
    {
        return new static(sprintf(array_shift($message), ...$message), 400);
    }

    public function abort()
    {
        die($this->message);
    }
}