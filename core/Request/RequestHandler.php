<?php namespace Core\Request;

use Exception;
use Core\Errors\ErroPageRender;

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
            ErroPageRender::create($e)->render();
        }
    }
}