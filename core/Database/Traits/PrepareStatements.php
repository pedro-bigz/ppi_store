<?php namespace Core\Database\Traits;

use PDO;
use Core\DateTime\Moment;

trait PrepareStatements
{
    protected $fetchMode = PDO::FETCH_ASSOC;

    protected function prepared($query)
    {
        $statement = $this->getPdo()->prepare($query);
        $statement->setFetchMode($this->fetchMode);

        return $statement;
    }

    public function makeStatement($query, $bindings = [])
    {
        return $this->run(trim($query), $bindings, function ($query, $bindings) {
            $statement = $this->bindValues(
                $this->getPdo()->prepare($query), $this->prepareBindings($bindings)
            );

            $this->recordsHaveBeenModified();

            return $statement->execute();
        });
    }
    
    public function makeStatementAndSetAsModified($query, $bindings = [])
    {
        return $this->run(trim($query), $bindings, function ($query, $bindings) {
            $statement = $this->bindValues(
                $this->getPdo()->prepare($query), $this->prepareBindings($bindings)
            );

            $statement->execute();
            
            $this->recordsHaveBeenModified(
                ($count = $statement->rowCount()) > 0
            );

            return $count;
        });
    }

    public function unprepared($query)
    {
        return $this->run(trim($query), [], function ($query) {
            $this->recordsHaveBeenModified(
                $change = $this->getPdo()->exec($query) !== false
            );

            return $change;
        });
    }
    
    public function bindValues($statement, $links)
    {
        foreach ($links as $k => $value) {
            $key = is_string($k) ? ':'.$k : $k + 1;
            $flag = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $statement->bindValue($key, $value, $flag);
        }

        return $statement;
    }

    public function prepareBindings(array $bindings)
    {
        foreach ($bindings as $key => $value) {
            if (Moment::isDate($value)) {
                $bindings[$key] = $value->format(Moment::FORMAT);
            } elseif (is_bool($value)) {
                $bindings[$key] = intval($value);
            }
        }

        return $bindings;
    }
}