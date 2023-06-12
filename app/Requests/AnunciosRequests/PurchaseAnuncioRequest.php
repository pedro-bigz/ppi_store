<?php namespace App\Requests\AnunciosRequests;

use Core\Request\FormRequest;
use Core\Request\Contracts\FormRequestInterface;

class PurchaseAnuncioRequest extends FormRequest implements FormRequestInterface
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'mensagem' => [self::REQUIRED, self::STRING_T],
            'nome' => [self::REQUIRED, self::STRING_T],
            'contato' => [self::REQUIRED, self::STRING_T],
        ];
    }

    public function getSanitized()
    {
        $sanitized = $this->validated();

        return $sanitized;
    }
}