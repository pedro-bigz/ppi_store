<?php namespace App\Models;

use Core\Database\Model;

class Anuncios extends Model
{
    protected string|null $table = 'anuncios';
    protected array $fillable = [
        'titulo',
        'preco',
        'categoria_id',
        'anunciante_id',
        'endereco_id',
        'descricao',
    ];
    protected array $dates = [
        'created_at',
        'updated_at'
    ];

    public function getCreatedAt()
    {
        return new DateTime($this->created_at);
    }
    
    public function getUpdatedAt()
    {
        return new DateTime($this->updated_at);
    }
}
