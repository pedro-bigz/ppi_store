<?php namespace Core\Database;

use Error;
use Closure;
use DateTime;
use Core\DateTime\Moment;
use BadMethodCallException;
use Core\Collections\Collection;
use Core\Database\Query\QueryBuilder;
use Core\Database\Query\BuilderBridge;
use Core\Exceptions\NotFoundException;

abstract class Model
{
    protected string|null $table = null;
    protected string $primaryKey = 'id';
    protected string $primaryKeyType = 'int';
    protected string $connection = 'mysql';

    public bool $timestamps = true;
    public bool $isLoaded = false;

    public string $created_at_column = 'created_at';
    public string $updated_at_column = 'updated_at';

    protected array $fillable = [];
    protected array $dates = [];
    protected array $with = [];

    protected array $attributes;

    public static function all($columns = ['*'])
    {
        return static::query()->get(
            is_array($columns) ? $columns : func_get_args()
        );
    }

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
        return $this->newModelQuery();//->with($this->with);
    }

    public function newModelQuery()
    {
        return $this->newBuilderBridgeInstance($this->newQueryBuilderInstance())->setModel($this);
    }

    public function newBuilderBridgeInstance(QueryBuilder $query)
    {
        return new BuilderBridge($query);
    }

    protected function newQueryBuilderInstance()
    {
        return $this->getConnection()->query();
    }

    public function setConnection($name)
    {
        $this->connection = $name;

        return $this;
    }

    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    public function setExists($exists)
    {
        $this->exists = $exists;

        return $this;
    }

    public function newInstance(array $attributes = [], $exists = false)
    {
        return static::create($attributes)
                ->setExists($exists)
                ->setConnection($this->getConnectionName())
                ->setTable($this->getTable());
    }

    public function getConnection()
    {
        return static::resolveConnection($this->getConnectionName());
    }

    public static function newConnection()
    {
        return ConnectionFactory::singleton();
    }

    public function getConnectionName()
    {
        return $this->connection;
    }

    public static function resolveConnection($connection = null)
    {
        return static::newConnection()->connection($connection);
    }

    public function newCollection(array|object $data = [])
    {
        return new Collection($models);
    }

    public function getFullKeyName()
    {
        return $this->getColumnFullName($this->getKeyName());
    }

    public function setLoaded($loaded)
    {
        $this->isLoaded = $loaded;
        return $this;
    }

    public function configLoaded()
    {
        return $this->setLoaded(true);
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this->configLoaded();
    }

    public function getTable()
    {
        return $this->table;// ?? snakeCase(class_basename($this));
    }

    public function getKeyName()
    {
        return $this->primaryKey;
    }

    public function getColumnFullName($column)
    {
        if (str_contains($column, '.')) {
            return $column;
        }

        return $this->getTable().'.'.$column;
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public function usesTimestamps()
    {
        return $this->timestamps;
    }

    public function getCreatedAtColumn()
    {
        return $this->created_at_column;
    }

    public function getUpdatedAtColumn()
    {
        return $this->updated_at_column;
    }

    public function setCreatedAtColumn($column)
    {
        $this->created_at_column = $column;
    }

    public function setUpdatedAtColumn($column)
    {
        $this->updated_at_column = $column;
    }

    public function setCreatedAt($value)
    {
        $this->{$this->getCreatedAtColumn()} = $value;

        return $this;
    }

    public function setUpdatedAt($value)
    {
        $this->{$this->getUpdatedAtColumn()} = $value;

        return $this;
    }

    public function getAttributeValue($key)
    {
        return in_array($key, $this->dates) ?
            Moment::create($this->attributes[$key]) : $this->attributes[$key];
    }

    public function __get($key)
    {
        if (! $key) {
            return null;
        }
        if (!in_array($key, [ 'id', ...$this->fillable, ...$this->dates ])) {
            return null;
        }
        if (array_key_exists($key, $this->attributes)) {
            return $this->getAttributeValue($key);
        }
        // if (array_key_exists($key, $this->relations)) {
        //     return $this->getRelationValue($key);
        // }
    }

    public function __call($method, $parameters)
    {
        try {
            return $this->newQuery()->{ $method }(...$parameters);
        } catch (NotFoundException $e) {
            throw $e;
        } catch (Error|BadMethodCallException $e) {
            throw new BadMethodCallException(sprintf(
                'Call to undefined method %s::%s()', static::class, $method
            ));
        }
    }

    public static function __callStatic($method, $parameters)
    {
        return static::create()->{ $method }(...$parameters);
    }

    public function transact(Closure $callback)
    {
        BuilderBridge::transaction($this->getConnection(), $callback);
    }
}
