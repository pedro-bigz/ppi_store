<?php namespace Core\Exceptions;

class FormSizeFileException extends CustomException
{
    public static function create($message)
    {
        return new static(sprintf(array_shift($message), ...$message), 400);
    }
}