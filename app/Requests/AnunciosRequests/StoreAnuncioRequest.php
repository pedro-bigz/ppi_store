<?php namespace App\Requests\AnunciosRequests;

use Core\Auth\Auth;
use Core\Request\FormRequest;
use Core\Request\Contracts\FormRequestInterface;

class StoreAnuncioRequest extends FormRequest implements FormRequestInterface
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'titulo' => [self::REQUIRED, self::STRING_T],
            'preco' => [self::REQUIRED, self::STRING_T],
            'categoria' => [self::REQUIRED, self::STRING_T],
            'endereco' => [self::REQUIRED, self::STRING_T],
            'descricao' => [self::NULLABLE, self::STRING_T],
            'file_bag' => [self::NULLABLE, self::STRING_T],
        ];
    }

    public function getSanitized()
    {
        $sanitized = $this->validated();

        $sanitized['categoria_id'] = intval($sanitized['categoria']);
        $sanitized['endereco_id'] = intval($sanitized['endereco']);
        $sanitized['file_bag'] = json_decode(html_entity_decode($sanitized['file_bag']), true);
        $sanitized['anunciante_id'] = Auth::user()->getAuthIdentifier();
        
        return $sanitized;
    }
}