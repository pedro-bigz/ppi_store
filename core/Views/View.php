<?php namespace Core\Views;

use Core\Exceptions\ViewNotFound;

class View
{
    private string $name;
    private array|null $data;
    private string|null $path;

    public function __construct(string $name, array|null $data)
    {
        $this->name = $name;
        $this->data = $data;
        $this->path = null;
    }

    public static function create(string $name, array|null $data)
    {
        return new static($name, $data);
    }

    public static function render(string $name, array|null $data)
    {
        return static::create($name, $data)->load();
    }

    public function load()
    {
        $this->path = ViewPath::resolver($this->name);
        
        if (!$this->pageExists()) {
            throw ViewNotFound::create();
        }
        
        $this->renderPage();
    }

    public function pageExists()
    {
        return !is_null($this->path);
    }

    public function renderPage()
    {
        extract($this->data);
        include $this->page;
    }
}