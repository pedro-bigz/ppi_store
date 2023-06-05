<?php namespace Core\Database;

use Core\Query\Builder;
use Core\Database\ConnectionResolver;

abstract class Model
{
    protected string|null $table = null;
    protected string $primaryKey = 'id';
    protected string $primaryKeyType = 'int';
    protected string $connection = 'mysql';

    protected array $fillable = [];
    protected array $dates = [];
    protected array $with = [];


    public static function all($columns = ['*'])
    {
        return static::query()->get(
            is_array($columns) ? $columns : func_get_args()
        );
    }

    // public function load($relations)
    // {
    //     $query = $this->newQueryWithoutRelationships()->with(
    //         is_string($relations) ? func_get_args() : $relations
    //     );

    //     $query->eagerLoadRelations([$this]);

    //     return $this;
    // }

    public static function create()
    {
        return new static;
    }

    public static function query()
    {
        return static::create()->newQuery();
    }

    public function newQuery()
    {
        return $this->newModelQuery()->with($this->with);
    }

    public function newModelQuery()
    {
        return $this->newBuilderBridgeInstance($this->newQueryBuilderInstance())->setModel($this);
    }

    public function newBuilderBridgeInstance(Builder $query)
    {
        return new BuilderBridge($query);
    }

    protected function newQueryBuilderInstance()
    {
        return $this->getConnection()->query();
    }

    public function getConnection()
    {
        return static::resolveConnection($this->getConnectionName());
    }

    public function getConnectionResolver()
    {
        return ConnectionFactory::instance();
    }

    public function getConnectionName()
    {
        return $this->connection;
    }

    public static function resolveConnection($connection = null)
    {
        return static::getConnectionResolver()->connection($connection);
    }
}
