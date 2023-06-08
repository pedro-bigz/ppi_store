<?php namespace Core\Database\Query;

use Closure;
use Core\Database\Connection;
use Core\Database\ConnectionInterface;
use Core\Database\Traits\JoinExpressionTrait;
use Core\Database\Traits\WhereExpressionTrait;
use Core\Database\Traits\HavingExpressionTrait;

class QueryBuilder
{
    private ConnectionInterface $connection;
    private string|null $query;
    private array $bindings;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->query = null;
        $this->bindings = [];
    }

    public function config()
    {
        return (func_num_args() > 0) ? $this->set(...func_get_args()) : $this;
    }

    public function set(string|null $query = null, array $bindings = [])
    {
        $this->query = $query;
        $this->bindings = $bindings;
        return $this;
    }

    public function get()
    {
        return [$this->query, $this->bindings];
    }

    public function setQuery(string $query)
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setBindings(array $bindings = [])
    {
        $this->bindings = $bindings;
        return $this;
    }

    public function addBindings($item)
    {
        $this->bindings[] = $item;
        return $this;
    }

    public function getBindings()
    {
        return $this->bindings;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function select()
    {
        return $this->config(...func_get_args())
                    ->getConnection()
                    ->select($this->query, $this->bindings);
    }

    public function insert()
    {
        return $this->config(...func_get_args())
                    ->getConnection()
                    ->insert($this->query, $this->bindings);
    }

    public function update()
    {
        return $this->config(...func_get_args())
                    ->getConnection()
                    ->update($this->query, $this->bindings);
    }

    public function delete()
    {
        return $this->config(...func_get_args())
                    ->getConnection()
                    ->delete($this->query, $this->bindings);
    }

    public function getLastInsertedId()
    {
        return $this->connection->getLastId();
    }

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollBack()
    {
        $this->connection->rollBack();
    }

    public function transaction(Closure $callback)
    {
        $this->connection->transaction($callback);
    }

    public static function transact($info, Closure $callback)
    {
        Connection::transaction($info, $callback);
    }
}
