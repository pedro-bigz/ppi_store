<?php namespace App\Requests\AuthRequests;

use Core\Request\FormRequest;
use Core\Request\Contracts\FormRequestInterface;

class RegisterRequest extends FormRequest implements FormRequestInterface
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => [self::REQUIRED, self::EMAIL_T],
            'password' => [self::REQUIRED, self::STRING_T],
            'first_name' => [self::REQUIRED, self::STRING_T],
            'last_name' => [self::REQUIRED, self::STRING_T],
            'fone' => [self::NULLABLE, self::STRING_T],
        ];
    }

    public function getSanitized()
    {
        $sanitized = $this->validated();
        $sanitized['password'] = hash($sanitized['password']);

        return $sanitized;
    }
}