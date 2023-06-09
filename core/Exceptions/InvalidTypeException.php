<?php namespace Core\Exceptions;

class InvalidTypeException extends ApplicationException
{
    public static function create($attribute, $type, $currentType)
    {
        return new static(
            "O tipo do campo {$attribute} deve ser {$attribute}. Tipo recebido ({$currentType})", 400
        );
    }
}