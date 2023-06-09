<?php namespace Core\Exceptions;

class NotFoundException extends ApplicationException
{
    public static function create($message = 'Página não encontrada')
    {
        return new static($message, 404);
    }

    public static function throwNotFoundExceptionIf($condition, $message)
    {
        if ((bool) $condition) {
            throw static::create($message);
        }
    }
}