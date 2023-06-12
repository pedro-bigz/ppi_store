<?php namespace Core\Database\Query;

use Closure;
use Core\Database\Model;
use Core\DateTime\Moment;
use Core\Database\Query\QueryBuilder;
use Core\Exceptions\NotFoundException;

class BuilderBridge
{
    protected $query;
    protected $model;

    public function __construct(QueryBuilder $query)
    {
        $this->query = $query;
    }

    public function make(array $attributes = [])
    {
        return $this->model->newInstance($attributes)
                            ->setConnection(
                                $this->query->getConnection()
                                            ->getName()
                            );
    }

    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    public function find(string|int $id, array $columns = ['*'])
    {
        return $this->select(
            columns:    $columns,
            where:      "{$this->model->getFullKeyName()} = :id",
            bindings:   compact("id"),
            first:      true,
        );
    }

    public function select(
        array $columns = ['*'],
        array|string|null $joins = null,
        ?string $where = null,
        int|string|null $limit = null,
        int|string|null $offset = null,
        ?string $groups = null,
        ?string $orderBy = null,
        ?string $orderDirection = 'asc',
        ?string $having = null,
        array $bindings = [],
        bool $first = false,
    ) {
        $select = implode(', ', $columns);

        if (is_array($joins)) {
            $joins = implode(' ', $joins);
        }
        if (is_string($where) && strlen($where) > 0) {
            $where = "WHERE {$where}";
        }
        if (! is_null($limit)) {
            $limit = "LIMIT {$limit}";
        }
        if (! is_null($offset)) {
            $offset = "OFFSET {$offset}";
        }
        if (is_string($groups) && strlen($groups) > 0) {
            $groups = "GROUP BY {$groups}";
        }
        if (is_string($orderBy) && strlen($orderBy) > 0) {
            $order = "ORDER BY {$orderBy} {$orderDirection}";
        }
        if (is_string($having) && strlen($having) > 0) {
            $having = "HAVING {$having}";
        }

        return $this->selectRaw(<<<SQL
            SELECT {$select} FROM {$this->model->getTable()} {$joins}
            {$where} {$order} {$limit} {$offset} {$groups} {$having}
        SQL, $bindings, $first);
    }

    public function inserts(array $items = [])
    {
        return $this->model->newCollection(
            array_map([$this, 'create'], $items)    
        );
    }

    public function create(array $items)
    {
        $items = [];
        foreach ($this->model->getFillable() as $column) {
            if (array_key_exists($column, $items)) {
                $items[$column] = $items[$column];
            }
        }

        $columns = implode(", ", array_keys($inputs));
        $values = ":" . implode(", :", array_keys($inputs));

        $this->query->insert(<<<SQL
            INSERT INTO {$this->model->getTable()} ({$columns}) VALUES ({$values})
        SQL, $inputs);
        
        return $this->find($this->query->getLastInsertedId());
    }

    public function update(
        array $columns,
        array|string|null $joins = null,
        string|null $where = null,
        int|string|null $limit = null,
        int|string|null $offset = null
    ) {
        $items = [];
        $column = $this->model->getKeyName();
        if (array_key_exists($column, $columns)) {
            $items[$column] = $columns[$column];
        }
        foreach ($this->model->getFillable() as $column) {
            if (array_key_exists($column, $columns)) {
                $items[$column] = $columns[$column];
            }
        }

        $set = implode(', ',
            array_map(
                fn($column) => "{$this->model->getColumnFullName($column)} = :{$column}",
                array_keys($items)
            )
        );

        if (is_array($joins)) {
            $joins = implode(' ', $joins);
        }
        if (is_string($where) && strlen($where) > 0) {
            $where = "WHERE {$where}";
        }
        if (! is_null($limit)) {
            $limit = "LIMIT {$limit}";
        }
        if (! is_null($offset)) {
            $offset = "OFFSET {$offset}";
        }

        return $this->updateRaw(<<<SQL
            UPDATE {$this->model->getTable()} {$joins}
            SET {$set} {$where} {$limit} {$offset}
        SQL, $items);
    }

    public function delete(
        array|string|null $joins = null,
        ?string $where = null,
        int|string|null $limit = null,
        int|string|null $offset = null,
        array $bindings = []
    ) {
        if (is_array($joins)) {
            $joins = implode(' ', $joins);
        }
        if (is_string($where) && strlen($where) > 0) {
            $where = "WHERE {$where}";
        }
        if (! is_null($limit)) {
            $limit = "LIMIT {$limit}";
        }
        if (! is_null($offset)) {
            $offset = "OFFSET {$offset}";
        }

        return $this->deleteRaw(<<<SQL
            DELETE FROM {$this->model->getTable()}
            {$joins} {$where} {$limit} {$offset}
        SQL, $bindings);
    }

    public function bindToLog($query, $bindings)
    {
        foreach ($bindings as $binding => $value) {
            $query = str_replace(':'.$binding, $value, $query);
        }

        return $query;
    }

    public function getData($data, $first = false)
    {
        if (! $first) {
            return $data;
        }
        return $data[0];
    }

    public function validateSelect($data, $query, $bindings)
    {
        if (count($data) === 0) {
            throw NotFoundException::create(
                "Item não encontrado! (SQL: {$this->bindToLog($query, $bindings)})"
            );
        }
        
        return $data;
    }

    public function selectRaw(string $query, array $bindings = [], bool $first = false)
    {
        return $this->model->setAttributes($this->getData(
            $this->query->select($query, $bindings), $first
        ));
    }

    public function createRaw(string $query, array $bindings = [])
    {
        $this->query->insert($query, $bindings);

        return $this->find(
            $this->query->getLastInsertedId()
        );
    }

    public function updateRaw($query, $bindings)
    {
        return $this->notifyChanges(
            $this->query->update($query, $bindings), 'updated', $bindings
        );
    }

    public function deleteByKey(string|int $id)
    {
        return $this->delete(
            where:      "{$this->model->getFullKeyName()} = :id",
            bindings:   compact("id")
        );
    }

    public function deleteRaw(string $query, array $bindings = [])
    {
        return $this->notifyChanges(
            $this->query->delete($query, $bindings), 'deleted', true
        );
    }

    public function notifyChanges(int $changes, string $method, $arguments = [])
    {
        if (! is_array($arguments)) {
            $arguments = [$arguments];
        }
        if ($changes > 0) {
            $this->model->{ $method }(...$arguments);
        }
        return $changes;
    }

    public function transaction(Closure $callback)
    {
        $this->query->transaction($callback);
    }

    public static function transact($info, Closure $callback)
    {
        QueryBuilder::transaction($info, $callback);
    }
}
