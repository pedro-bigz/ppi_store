<?php namespace App\Exceptions;

use Exception;

class InexistentClass extends Exception {
    public static function create($classname)
    {
        return new static("A classe {$classname} não foi encontrada", 404);
    }
}