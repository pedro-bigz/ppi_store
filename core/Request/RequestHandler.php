<?php namespace Core\Request;

use Exception;
use Core\Debug\DebugBacktrace;
use Core\Views\ErroPageRender;

class RequestHandler
{
    public function __construct($callback)
    {
        [$this->object, $this->method] = $callback;
    }

    public function handle()
    {
        try {
            return $this->object->{ $this->method }();
        } catch (Exception $e) {
            if (DEBUG === false) {
                ErroPageRender::create($e)->render();
            } else {
                DebugBacktrace::create($e)->render();
            }
        }
    }
}