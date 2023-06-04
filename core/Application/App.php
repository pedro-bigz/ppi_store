<?php namespace Core\Application;

use Core\Routing\Router;
use Core\Request\RequestHandler;

class App
{
    private Router $router;
    private static $instance;

    private function __construct(Router $router)
    {   
        $this->router = $router;
    }

    public static function singleton(Router $router)
    {
        if (static::$instance == null) {
            static::$instance = new static($router);
        }
        return static::$instance;
    }

    public function run()
    {
        $this->capture([$this->router, 'load'])->handle();
    }

    public function capture($callback)
    {
        return new RequestHandler($callback);
    }
}