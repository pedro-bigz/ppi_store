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
        $this->middlewares[0] = $this->getMiddlewareInstance($classname);

        foreach ($middlewares as $key => $classname) {
            $current = $this->getMiddlewareInstance($classname);

            $this->middlewares[$key]->setNext($current);
            $this->middlewares[$key] = $current;
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
        return $this->getInstance(MIDDLEWARES_NAMESPACE.'\\'.$classname);
    }
}