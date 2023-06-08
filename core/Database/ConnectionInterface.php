<?php namespace Core\Database;

use Closure;

interface ConnectionInterface
{
    public function select($query, $bindings = []);
    public function cursor($query, $bindings = []);
    public function insert($query, $bindings = []);
    public function update($query, $bindings = []);
    public function delete($query, $bindings = []);
    public function makeStatement($query, $bindings = []);
    public function makeStatementAndSetAsModified($query, $bindings = []);
    public function unprepared($query);
    public function bindValues($statement, $links);
    public function prepareBindings(array $bindings);
    public function transaction(Closure $callback);
    public function beginTransaction();
    public function commit();
    public function rollBack();
    public function getDatabaseName();
}
