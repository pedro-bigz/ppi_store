<?php namespace Core\Routing;

use Core\Routing\Route;
use Core\Request\Request;
use Core\Routing\ControllerRunner;
use Core\Routing\MiddlewareRunner;

class RouteAction {
    private $route;
    private $request;
    private $middlewareRunner;
    private $controllerRunner;

    public function __construct(Route $route, MiddlewareRunner $middlewareRunner, ControllerRunner $controllerRunner, Request $request)
    {
        $this->route = $route;
        $this->request = $request;
        $this->middlewareRunner = $middlewareRunner;
        $this->controllerRunner = $controllerRunner;
    }

    public function run()
    {
        $this->middlewareRunner->run();
        $this->controllerRunner->run();
    }
}