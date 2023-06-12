<?php namespace App\Models;

use Core\Database\Model;

class Interesses extends Model
{
    protected string|null $table = 'interesses';
    protected array $fillable = [
        'nome',
        'contato',
        'mensagem',
        'anuncio_id',
    ];
    protected array $dates = [
        'created_at',
        'updated_at'
    ];

    public function getResourceUrl()
    {
        return url("/interesses/{$this->id}");
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
