<?php namespace Core\Database;

interface ConnectionFactoryInterface
{
    public function connection($name = null);
    public function getDefaultConnection();
    public function setDefaultConnection($name);
    public function addConnection(string $name, ConnectionInterface $connection);
}
