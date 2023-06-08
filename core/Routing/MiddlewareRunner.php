<?php namespace Core\Routing;

use Core\Request\Request;
use Core\Application\Runner;
use Core\Application\RunnerInterface;

class MiddlewareRunner extends Runner implements RunnerInterface
{
    private $middlewares;
    private $request;

    public function __construct(array $middlewares, Request $request)
    {
        $this->middlewares = $middlewares;
        $this->request = $request;
    }

    public function config(): RunnerInterface
    {
        $middlewares = $this->middlewares;

        if (empty($middlewares)) {
            return $this;
        }

        $classname = array_shift($middlewares);
        $middleware = $this->getMiddlewareInstance($classname);

        foreach ($middlewares as $classname) {
            $current = $this->getMiddlewareInstance($classname);

            $middleware->setNext($current);
            $middleware = $current;
        }

        return $this;
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