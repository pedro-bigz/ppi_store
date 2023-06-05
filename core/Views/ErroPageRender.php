<?php namespace Core\Views;

class ErroPageRender
{
    private const PAGES = [
        401 => '401_NOT_AUTHORIZED',
        403 => '403_FORBIDDEN',
        404 => '404_NOT_FOUND'
    ];
    private $error;

    public function __construct($error)
    {
        $this->error = $error;
    }

    public static function create($error)
    {
        return new static($error);
    }

    public function render()
    {
        $page = $this->hasPage() ?
            $this->getPage() : $this->getDefaultPage();
        $this->renderPage($page);
    }

    public function getDefaultPage()
    {
        return self::PAGES[404] . '.php';
    }

    public function getPage()
    {
        return self::PAGES[$this->error->getCode()] . '.php';
    }

    public function hasPage()
    {
        return array_key_exists($this->error->getCode(), self::PAGES);
    }

    public function renderPage($page)
    {
        include ERROR_VIEW_PATH . '/' . $page;
    }
}