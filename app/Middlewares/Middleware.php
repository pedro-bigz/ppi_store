<?php namespace Middlewares;

use Core\Request;

abstract class Middleware
{
    private Middleware $next;

    public function __construct()
    {
        $this->next = null;
    }

    abstract function handler(Request $request);

    public function setNext($next)
    {
        $this->next = $next;
    }

    public function next()
    {
        if (!is_null($this->next)) {
            $this->next->handler();
        }
    }
}