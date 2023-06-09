<?php namespace App\Requests\AuthRequests;

use Core\Request\FormRequest;
use Core\Request\Contracts\FormRequestInterface;

class LoginRequest extends FormRequest implements FormRequestInterface
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
        ];
    }

    public function getSanitized()
    {
        return $this->validated();
    }
}