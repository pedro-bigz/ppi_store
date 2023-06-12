<?php namespace Core\Exceptions;

class ViewNotFound extends ApplicationException
{
    public static function create(...$message)
    {
        return new static(sprintf(array_shift($message), ...$message), 400);
    }
}