<?php namespace Core\Request\Factory;

use Core\Request\Request;

class RequestFactory
{
    public static function make()
    {
        // string $uri,
        // string $method = 'GET',
        // array $parameters = [],
        // array $cookies = [],
        // array $files = [],
        // array $server = [],
        return Request::create(
            uri: $_SERVER['REQUEST_URI'],
            method: $_SERVER['REQUEST_METHOD'],
            // query: array_merge($_GET, $_POST),
            request: $_REQUEST,
            cookies: $_COOKIE,
            files: $_FILES,
            server: $_SERVER,
        );
    }
}