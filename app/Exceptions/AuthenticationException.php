<?php namespace App\Exceptions;

use Core\Exceptions\ApplicationException;

class AuthenticationException extends ApplicationException
{
    public const DEFAULT_FLAG = 3;
    public const USER_DONT_EXISTS = 0;
    public const INVALID_EMAIL = 1;
    public const INVALID_PASSWORD = 2;
    public const INVALID_LOGIN = 3;
    public const EMAIL_EXISTS = 4;
    public const INVALID_INICIALIZATION = 5;
    
    public static function create($type = self::DEFAULT_FLAG, $code = 403)
    {
        if ($type < self::USER_DONT_EXISTS || $type > self::INVALID_INICIALIZATION) {
            $type = self::INVALID_INICIALIZATION;
        }

        $messages = [
            'Usuário não cadastrado',
            'Email inválido',
            'Senha incorreta',
            'Falha ao autenticar usuário',
            'O email informado já foi cadastrado',
            'Indice fora da area de abrangencia',
        ];

        return new static($messages[$type], $code);
    }
}