<?php namespace Core\Exceptions;

use Exception;

class NotFoundError extends Exception
{
    public static function create()
    {
        return new static('Página não encontrada', 404);
    }
}