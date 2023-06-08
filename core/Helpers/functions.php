<?php

use Symfony\Component\VarDumper\VarDumper;
use Core\Views\View;

if (! function_exists('view')) {
    function view(string $name, array|null $data)
    {
        return View::render($name, $data);
    }
}

if (! function_exists('factory')) {
    function factory(string $classname)
    {
        return new $classname;
    }
}

if (!function_exists('dump')) {
    function dump($var, ...$moreVars)
    {
        VarDumper::dump($var);

        foreach ($moreVars as $v) {
            VarDumper::dump($v);
        }

        if (1 < func_num_args()) {
            return func_get_args();
        }

        return $var;
    }
}

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        if (!in_array(\PHP_SAPI, ['cli', 'phpdbg'], true) && !headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }

        foreach ($vars as $v) {
            VarDumper::dump($v);
        }

        exit(1);
    }
}
