<?php namespace Core\Database\Traits;

use Closure;
use Exception;
use Core\Query\BuilderBridge;

trait TransactionTrait
{
    public function beginTransaction()
    {
        $this->getPdo()->beginTransaction();
    }

    public function commit()
    {
        $this->getPdo()->commit();
    }

    public function rollBack()
    {
        $this->getPdo()->rollBack();
    }

    public function transaction(Closure $callback)
    {
        try {
            $this->beginTransaction();
            call_user_func($callback, new BuilderBridge($this->query()));
            $this->commit();
        } catch (Exception $e) {
            $this->rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public static function transact($info, Closure $callback)
    {
        (new static($info))->transaction($callback);
    }
}