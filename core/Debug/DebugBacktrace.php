<?php namespace Core\Debug;

use Throwable;

class DebugBacktrace
{
    private const PAGE = 'DEBUG_PAGE';
    private Throwable $error;
    
    public function __construct(Throwable $error)
    {        
        $this->error = $error;
    }
    
    public static function create(Throwable $error)
    {
        return new static($error);        
    }

    public function render()
    {
        $this->renderPage($this->getPage());
    }

    public function getPage()
    {
        return self::PAGE . '.php';
    }

    public function hasPage()
    {
        return array_key_exists($this->error->getCode(), self::PAGES);
    }

    public function renderPage($page)
    {
        $error = $this->error;
        include ERROR_VIEW_PATH . '/' . $page;
    }
}