<?php namespace Core\Exceptions;

class AuthorizationException extends ApplicationException
{
    public static function create()
    {
        return new static(
            "você não possui autorização para acessar este recurso", 401
        );
    }
}