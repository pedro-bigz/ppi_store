<?php namespace Core\Exceptions;

use Exception;

class CustomException extends Exception
{
    public static function createf($message, $code)
    {
        return new static(sprintf(array_shift($message), ...$message), $code);
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