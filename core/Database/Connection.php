<?php namespace Core\Database;

use Closure;
use Core\Database\Query\QueryBuilder;
use Core\Database\MySqlConnection;
use Core\Exceptions\QueryException;
use Core\Database\Traits\TransactionTrait;
use Core\Database\Traits\PrepareStatements;

class Connection implements ConnectionInterface
{
    use PrepareStatements;
    use TransactionTrait;

    private $config = [];
    private $connection;
    protected $database;
    protected $tablePrefix = '';
    protected $events;
    protected $recordsModified;
    protected $transactionsManager;

    public function __construct(array $info)
    {
        $this->config = $info;
        $this->database = $this->config['database'];
        $this->tablePrefix = $this->config['table_prefix'] ?? '';
        $this->connection = MySqlConnection::connect($info);
    }

    public function query()
    {
        return new QueryBuilder($this);
    }

    public function getLastId()
    {
        return $this->connection->lastInsertId();
    }

    public function select($query, $bindings = [])
    {
        return $this->run($query, $bindings, function ($query, $bindings) {
            $statement = $this->bindValues(
                $this->prepared($query), $this->prepareBindings($bindings)
            );

            $statement->execute();

            return $statement->fetchAll();
        });
    }

    public function cursor($query, $bindings = [])
    {
        $statement = $this->run($query, $bindings, function ($query, $bindings) {
            $statement = $this->bindValues(
                $this->prepared($query), $this->prepareBindings($bindings)
            );

            $statement->execute();

            return $statement;
        });

        while ($record = $statement->fetch()) {
            yield $record;
        }
    }

    public function insert($query, $bindings = [])
    {
        return $this->makeStatement($query, $bindings);
    }

    public function update($query, $bindings = [])
    {
        return $this->makeStatementAndSetAsModified($query, $bindings);
    }

    public function delete($query, $bindings = [])
    {
        return $this->makeStatementAndSetAsModified($query, $bindings);
    }

    public function recordsHaveBeenModified($value = true)
    {
        if (! $this->recordsModified) {
            $this->recordsModified = $value;
        }
    }

    protected function run($query, $bindings, Closure $callback)
    {
        $start = microtime(true);

        try {
            return $this->runQueryCallback($query, $bindings, $callback);
        } catch (QueryException $e) {
            return $this->checkConnectionAndTryAgain(
                $e, $query, $bindings, $callback
            );
        }
    }
    
    protected function runQueryCallback($query, $bindings, Closure $callback)
    {
        try {
            return $callback($query, $bindings);
        } catch (Exception $e) {
            throw new QueryException($query, $this->prepareBindings($bindings), $e);
        }
    }

    protected function checkConnectionAndTryAgain(QueryException $e, $query, $bindings, Closure $callback)
    {
        if (!$e->isCausedByLostConnection()) {
            throw $e;
        }

        return $this->reconnect()->runQueryCallback($query, $bindings, $callback);
    }

    public function reconnect()
    {
        return $this->setPdo(MySqlConnection::connect($this->config));
    }

    public function getPdo()
    {
        return $this->connection->getPdo();
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    public function getName()
    {
        return $this->getConfig('name');
    }

    public function getConfig($option = null)
    {
        return $this->config[$option] ?? null;
    }

    public function getDriverName()
    {
        return $this->getConfig('driver');
    }

    public function getDatabaseName()
    {
        return $this->database;
    }

    public function setDatabaseName($database)
    {
        $this->database = $database;

        return $this;
    }

    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    public function setTablePrefix($prefix)
    {
        $this->tablePrefix = $prefix;

        return $this;
    }
}
