<?php namespace App\Exceptions;

use Exception;

class NotFoundError extends Exception {
    public static function create()
    {
        return new static('Página não encontrada', 404);
    }
}