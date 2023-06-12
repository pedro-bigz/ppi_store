<?php namespace Core\Exceptions;

use Exception;
use Throwable;

class ApplicationException extends Exception
{
    private $customTrace;
    public function __construct($message = "", $code = 0, ?Throwable $previous = null, $customTrace = [])
    {
        parent::__construct($message, $code, $previous);
        $this->customTrace = $customTrace;
    }

    public static function createf($message, $code)
    {
        return new static(sprintf(array_shift($message), ...$message), $code);
    }

    public static function casting(Throwable $e)
    {
        return new static($e->getMessage(), $e->getCode(), $e->getPrevious(), $e->getTrace());
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

    public function getCustomTrace()
    {
        return $this->customTrace;
    }

    public function getDebugTrace()
    {
        return DEBUG ? ($this->getCustomTrace() ?: $this->getTrace()) : [];
    }
}