<?php namespace Core\Errors;

class ErroPageRender {
    private const PAGES = [401 => '401_NOT_AUTHORIZED', 403 => '403_FORBIDDEN', 404 => '404_NOT_FOUND'];
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
        if ($this->hasPage()) {
            $this->renderPage(
                ERROR_VIEW_PATH . '/' . $this->getPage()
            );
        }
    }

    public function getPage()
    {
        return self::PAGES[$this->error->getCode()] . 'php';
    }

    public function hasPage()
    {
        return array_key_exists($this->error->getCode(), self::PAGES);
    }

    public function renderPage($page)
    {
        echo file_get_contents($page);
    }
}