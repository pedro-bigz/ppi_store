<?php namespace Core\Routing;

use Throwable;
use Core\Routing\Route;
use Core\Request\Request;
use Core\Routing\ControllerRunner;
use Core\Routing\MiddlewareRunner;
use Core\Exceptions\ApplicationException;

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
        try {
            $this->middlewareRunner->run();
            $this->controllerRunner->run();
        } catch (ApplicationException $e) {
            $e->abort();
        } catch (Throwable $e) {
            ApplicationException::casting($e)->abort();
        }
    }
}