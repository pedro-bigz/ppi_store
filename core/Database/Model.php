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
    public bool $updated = false;
    public bool $deleted = false;

    public string $created_at_column = 'created_at';
    public string $updated_at_column = 'updated_at';

    protected array $fillable = [];
    protected array $dates = [];
    protected array $with = [];

    protected array $attributes = [];

    public static function all($columns = ['*'])
    {
        return static::query()->get(
            is_array($columns) ? $columns : func_get_args()
        );
    }

    public static function make()
    {
        return new static;
    }

    public static function query()
    {
        return static::make()->newQuery();
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
        return $this->fillable ?: [];
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

    public function getAttributes()
    {
        return $this->attributes ?: [];
    }

    public function getAll()
    {
        return $this->getAttributes();
    }

    public function get($key)
    {
        return $this->getAttribute($key) ?: null;
    }

    public function first()
    {
        return $this->get(0);
    }

    public function getAttribute($key)
    {
        return in_array($key, $this->dates) ?
            Moment::create($this->attributes[$key]) : $this->attributes[$key];
    }

    public function __get($key)
    {
        if (! $key) {
            return null;
        }
        if (!in_array($key, [ $this->primaryKey, ...$this->fillable, ...$this->dates ])) {
            return null;
        }
        if (array_key_exists($key, $this->attributes)) {
            return $this->getAttribute($key);
        }
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
    
    public function update($items)
    {
        if (! array_key_exists($this->getKeyName(), $this->getAttributes())) {
            return $this->newQuery()->update(...func_get_args());
        }
        if ($this->isLoaded === false) {
            return;
        }
        $items[$this->getKeyName()] = $this->getAttribute(
            $this->getKeyName()
        );
        $this->newQuery()->update(
            columns: $items,
            where: "{$this->getFullKeyName()} = :{$this->getKeyName()}"
        );
    }
    
    public function delete()
    {
        if (! array_key_exists($this->getKeyName(), $this->getAttributes())) {
            return $this->newQuery()->delete(...func_get_args());
        }
        if ($this->isLoaded === false) {
            return;
        }
        $this->newQuery()->deleteByKey(
            $this->getAttribute($this->getKeyName())
        );
    }

    public function updated($data)
    {
        $this->updated = true;
        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    public function deleted($data)
    {
        $this->deleted = true;
        $this->attributes = [];
    }

    public function isEmpty()
    {
        return empty($this->getAttributes());    
    }

    public static function __callStatic($method, $parameters)
    {
        return static::make()->{ $method }(...$parameters);
    }

    public function transact(Closure $callback)
    {
        BuilderBridge::transaction($this->getConnection(), $callback);
    }
}