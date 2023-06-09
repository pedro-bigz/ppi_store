<?php namespace Core\Exceptions;

class InexistentClass extends ApplicationException
{
    public static function create($classname)
    {
        return new static("A classe {$classname} não foi encontrada", 500);
    }
}