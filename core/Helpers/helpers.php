<?php

use Core\Views\View;

if (!method_exists('view')) {
    function view(string $name, array|null $data)
    {
        return View::render($name, $data);
    }
}