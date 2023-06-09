<?php namespace Core\Exceptions;

class BadRequestException extends CustomException
{
    public static function create($message)
    {
        return new static(sprintf(array_shift($message), ...$message), 400);
    }
}