<?php namespace Core\Routing;

use Core\Request\Request;
use Core\Request\Factory\RequestFactory;

class ConfigRoute
{
    private $route;
    private $request;
    private $middlewareRunner;
    private $controllerRunner;

    public function __construct(Route $route)
    {
        // var_dump($route);
        $this->route = $route;
    }

    public function init(): void
    {
        $this->route->init();

        $this->request = $this->extractRequest();
        $this->middlewareRunner = $this->initMiddleware()->config();
        $this->controllerRunner = $this->initController()->config();
    }

    public function extractRequest(): Request
    {
        return RequestFactory::make();
    }

    public function initMiddleware(): MiddlewareRunner
    {
        return new MiddlewareRunner($this->route->getMiddlewares(), $this->request);
    }

    public function initController(): ControllerRunner
    {
        return new ControllerRunner($this->route->getController(), $this->route->getParams(), $this->request);        
    }
}