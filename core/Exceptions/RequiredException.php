<?php namespace Core\Exceptions;

class RequiredException extends CustomException
{
    public static function create($attribute)
    {
        return new static("O campo {$attribute} é obrigatório.", 400);
    }
}