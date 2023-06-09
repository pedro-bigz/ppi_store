<?php namespace Core\Exceptions;

class NoFileException extends CustomException
{
    public static function create($message)
    {
        return new static(sprintf(array_shift($message), ...$message), 400);
    }
}