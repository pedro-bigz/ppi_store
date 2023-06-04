<?php namespace Core\Routing;

use Core\Request\Factory\RequestFactory;

class ConfigRoute
{
    private $route;
    private $request;
    private $middlewareRunner;
    private $controllerRunner;

    public function __construct(Route $route)
    {
        var_dump($route);
        $this->route = $route;
    }

    public function configure()
    {
        $this->route->init();

        $this->request = $this->extractRequest();        
        $this->middlewareRunner = $this->initMiddleware();
        $this->controllerRunner = $this->initController();
    }

    public function extractRequest()
    {
        return RequestFactory::make();
    }

    public function initMiddleware()
    {
        return new MiddlewareRunner($this->route->middlewares, $this->request);
    }

    public function initController()
    {
        return new ControllerRunner($this->route->controller, $this->request);        
    }
}