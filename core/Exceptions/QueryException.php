<?php namespace Core\Exceptions;

use Exception;
use Throwable;
use PDOException;

class QueryException extends PDOException
{
    public function __construct($sql, array $bindings, Throwable $previous)
    {
        parent::__construct('', $previous->getCode(), $previous);

        $this->sql = $sql;
        $this->bindings = $bindings;
        $this->message = $this->formatMessage($sql, $bindings, $previous);

        if ($previous instanceof PDOException) {
            $this->errorInfo = $previous->errorInfo;
        }
    }

    public function isCausedByLostConnection()
    {
        $messages = [
            'ORA-03114',
            'reset by peer',
            'Lost connection',
            'SSL: Broken pipe',
            'query_wait_timeout',
            'Error while sending',
            'server has gone away',
            'Login timeout expired',
            'Transaction() on null',
            'is dead or not enabled',
            'Resource deadlock avoided',
            'SSL: Connection timed out',
            'Communication link failure',
            'no connection to the server',
            'TCP Provider: Error code 0x68',
            'connection is no longer usable',
            'Packets out of order. Expected',
            'TCP Provider: Error code 0x274C',
            'Adaptive Server connection failed',
            'Physical connection is not usable',
            'decryption failed or bad record mac',
            'Error writing data to the connection',
            'Temporary failure in name resolution',
            'server closed the connection unexpectedly',
            'SQLSTATE[HY000] [2002] Connection refused',
            'SQLSTATE[HY000] [2002] Connection timed out',
            'SQLSTATE[08S01]: Communication link failure',
            'SSL connection has been closed unexpectedly',
            'SQLSTATE[08006] [7] could not translate host name',
            'child connection forced to terminate due to client_idle_limit',
            'SQLSTATE[HY000]: General error: 7 SSL SYSCALL error: EOF detected',
            'SQLSTATE[HY000]: General error: 7 SSL SYSCALL error: No route to host',
            'running with the --read-only option so it cannot execute this statement',
            'SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo failed: Try again',
            'SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo failed: Name or service not known',
            'SQLSTATE[08006] [7] could not connect to server: Connection refused Is the server running on host',
            'SQLSTATE[HY000]: General error: 1105 The last transaction was aborted due to Seamless Scaling. Please retry.',
            'The client was disconnected by the server because of inactivity. See wait_timeout and interactive_timeout for configuring this behavior.',
            'The connection is broken and recovery is not possible. The connection is marked by the client driver as unrecoverable. No attempt was made to restore the connection.',
        ];

        return in_array($this->getPrevious()->getMessage(), $messages);
    }
}
