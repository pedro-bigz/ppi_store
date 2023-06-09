<?php namespace Core\Exceptions;

use Exception;
use Throwable;

class ApplicationException extends Exception
{
    public static function createf($message, $code)
    {
        return new static(sprintf(array_shift($message), ...$message), $code);
    }

    public static function casting(Throwable $e)
    {
        return new static($e->getMessage(), $e->getCode(), $e->getPrevious());
    }

    public function abort()
    {
        http_response_code($this->getCode());
        die($this->encodeToReply($this->getMessage()));
    }

    public function encodeToReply($message)
    {
        return json_encode([
            'message' => $message,
            'exception' => 'Error',
            'code' => $this->getCode(),
            'trace' => $this->getDebugTrace(),
        ]);
    }

    public function getDebugTrace()
    {
        return DEBUG ? $this->getTrace() : [];
    }
}