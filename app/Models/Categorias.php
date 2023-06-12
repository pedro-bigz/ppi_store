<?php namespace App\Models;

use Core\Database\Model;

class Categorias extends Model
{
    protected string|null $table = 'categorias';
    protected array $fillable = [
        'nome',
        'descricao',
    ];
    protected array $dates = [
        'created_at',
        'updated_at'
    ];

    public function getResourceUrl()
    {
        return url("/categorias/{$this->id}");
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
