<?php namespace Core\Routing;

use Core\Runner;
use Core\Request\Request;

class MiddlewareRunner extends Runner
{
    private $middlewares;
    private $request;

    public function __construct(array $middlewares, Request $request)
    {
        $this->middlewares = $middlewares;
        $this->request = $request;
    }

    public function config()
    {
        $middlewares = $this->middlewares;

        $classname = array_shift($middlewares);
        $middleware = $this->getMiddlewareInstance($classname);

        foreach ($middlewares as $classname) {
            $current = $this->getMiddlewareInstance($classname);

            $middleware->setNext($current);
            $middleware = $current;
        }
    }

    public function run()
    {
        if (!empty($this->middlewares)) {
            $this->middlewares[0]->handler($this->request);
        }
    }

    public function getMiddlewareInstance($classname)
    {
        return $this->getInstance($classname);
    }
}