<?php namespace Core\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public static function create($message = 'Página não encontrada')
    {
        return new static($message, 404);
    }

    public static function throwNotFoundExceptionIf($condition, $message)
    {
        if ((bool) $condition) {
            static::create($message);
        }
    }
}