<?php namespace Core\Request;

use Throwable;
use Core\Debug\DebugBacktrace;
use Core\Views\ErroPageRender;
use Core\Exceptions\ApplicationException;

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
        } catch (Throwable $e) {
            $request = Request::make();
            if (DEBUG === false) {
                ErroPageRender::create($e)->render();
            } else if (!$request->ajax()) {
                DebugBacktrace::create($e)->render();
            } else {
                ApplicationException::casting($e)->abort();
            }
        }
    }
}