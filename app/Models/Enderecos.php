<?php namespace App\Models;

use Core\Database\Model;

class Enderecos extends Model
{
    protected string|null $table = 'enderecos';
    protected array $fillable = [
        'cep',
        'logradouro',
        'numero',
        'bairro',
        'cidade',
        'uf',
    ];
    protected array $dates = [
        'created_at',
        'updated_at'
    ];

    public function getResourceUrl()
    {
        return url("/enderecos/{$this->id}");
    }

    public function getCreatedAt()
    {
        return new DateTime($this->created_at);
    }
    
    public function getUpdatedAt()
    {
        return new DateTime($this->updated_at);
    }
}
