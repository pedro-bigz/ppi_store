<?php namespace Core\Database;

interface ConnectionFactoryInterface
{
    public function connection(string|null $name = null);
    public function getDefaultConnection();
    public function setDefaultConnection(string $name);
    public function addConnection(string $name, ConnectionInterface $connection);
}
