<?php namespace Core\Exceptions;

class InvalidValueException extends ApplicationException
{
    public static function create($attribute)
    {
        return new static("Valor invalido recebido no campo {$attribute}.", 400);
    }
}