<?php namespace Core\Database;

final class ConnectionFactory implements ConnectionFactoryInterface
{
    protected static $instance;

    protected $connections = [];
    protected $default = 'mysql';

    private function __construct(array $connections = [])
    {
        foreach ($connections as $name => $connection) {
            $this->addConnection($name, $connection);
        }
    }

    public function instance(array $connections = [])
    {
        if (self::$instance == null) {
            self::$instance = new self($connections);
        }
        return self::$instance;
    }

    public function connection(string $name = null)
    {
        if (is_null($name)) {
            $name = $this->default;
        }
        if (!array_key_exists($name, $this->connections)) {
            $this->addConnection($name, $this->makeConnection($name)); 
        }

        return $this->connections[$name];
    }

    public function findConnectionInfo(string $name)
    {
        $connections = DB_CONNECTIONS;

        if (!array_key_exists($name, $connections)) {
            throw ConnectionNotFound::create();
        }

        return $connection[$name];
    }

    public function makeConnection(string $name)
    {
        return new Connection($this->findConnectionInfo($name));
    }

    public function addConnection(string $name, ConnectionInterface $connection)
    {
        $this->connections[$name] = $connection;
    }

    public function hasConnection(string $name)
    {
        return isset($this->connections[$name]);
    }
    
    public function getDefaultConnection()
    {
        return $this->default;
    }
    
    public function setDefaultConnection(string $name)
    {
        $this->default = $name;
    }
}
