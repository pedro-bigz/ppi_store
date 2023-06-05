<?php namespace Core\Model;

abstract class Model
{
    protected string|null $table = null;
    protected string $primaryKey = 'id';
    protected string $connection = 'default';

    protected array $fillable = [];
    protected array $dates = [];
    protected array $with = [];

    
}
