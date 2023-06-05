<?php namespace Core\Routing;

use Core\Exceptions\NotFoundError;

class Router
{
    private $routes = [];
    private $request = [];

    public function __construct(Routes $routes)
    {
        $this->routes = $routes;
        $this->request = [
            'uri' => $_SERVER['REQUEST_URI'],
            'domain' => $_SERVER['HTTP_HOST'],
            'method' => strtoupper($_SERVER['REQUEST_METHOD']),
        ];
    }

    public static function make()
    {
        return static::create(Routes::create());
    }

    public static function create(Routes $routes)
    {
        return new static($routes);
    }

    public function config(Route $route)
    {
        $config = new ConfigRoute($route);
        $config->init();
    }

    private function resolve()
    {
        $route = $this->routes->get($this->request['method'], $this->request['uri']);

        if (is_null($route)) {
            throw NotFoundError::create();
        }

        return $route;
    }

    public function load()
    {
        return $this->config($this->resolve());
    }
}