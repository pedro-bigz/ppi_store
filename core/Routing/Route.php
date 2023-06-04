<?php namespace Core\Routing;

class Route
{
    private $route;
    private $params;
    private $controller;
    private $middlewares;
    private $method;

    public function __construct(string $method, array $route, array $params = [])
    {
        $this->route = $route;
        $this->params = $params;
        $this->method = $method;
    }

    public static function create(string $method, array $route, array $params)
    {
        return new static($method, $route, $params);
    }

    public function init()
    {
        $this->setController();
        $this->setMiddlewares();
    }

    public function setMiddlewares()
    {
        $this->middlewares = $this->route['middlewares'] ?? [];
    }

    public function setController()
    {
        $this->controller = $this->route['controller'];
    }
}