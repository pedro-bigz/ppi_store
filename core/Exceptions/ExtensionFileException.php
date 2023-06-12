<?php namespace Core\Exceptions;

class ExtensionFileException extends ApplicationException
{
    public static function create(...$message)
    {
        return new static(sprintf(array_shift($message), ...$message), 400);
    }
}