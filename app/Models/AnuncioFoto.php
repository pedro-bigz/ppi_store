<?php namespace App\Models;

use Core\Database\Model;

class AnuncioFoto extends Model
{
    protected string|null $table = 'anuncio_fotos';
    protected array $fillable = [
        'filename',
        'folder',
        'anuncio_id',
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
