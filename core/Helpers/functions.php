<?php

use Symfony\Component\VarDumper\VarDumper;
use Core\Views\View;

if (! function_exists('view')) {
    function view(string $name, array|null $data = [])
    {
        return View::render($name, $data);
    }
}

if (! function_exists('_')) {
    function _(string $text)
    {
        echo htmlspecialchars($text);
    }
}

if (! function_exists('hash')) {
    function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}

if (! function_exists('response')) {
    function response($response, int|null $code = 200)
    {
        http_response_code($code);
        return json_encode($response);
    }
}

if (! function_exists('factory')) {
    function factory(string $classname)
    {
        return new $classname;
    }
}

if (! function_exists('url')) {
    function url(string $uri = '')
    {
        return "https://{$_SERVER['HTTP_HOST']}{$uri}";
    }
}

if (! function_exists('currentUrl')) {
    function currentUrl()
    {
        return url($_SERVER['REQUEST_URI']);
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
        foreach ($vars as $v) {
            VarDumper::dump($v);
        }

        exit(1);
    }
}
