<?php namespace App\Models;

use Core\Database\Model;

class Anunciantes extends Model
{
    protected string|null $table = 'anunciantes';
    protected array $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'fone',
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
