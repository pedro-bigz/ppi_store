<?php

$routes = [
    'GET::/' => [
        'controller' => '',
    ],
    'GET::/register' => [
        'controller' => '',
    ],
    'GET::/anuncios' => [
        'controller' => '',
        'middlewares' => []
    ],
    'GET::/anuncios/:id' => [
        'controller' => '',
        'middlewares' => []
    ],
    'GET::/anuncios/create' => [
        'controller' => '',
        'middlewares' => []
    ],
    'GET::/anuncios/edit' => [
        'controller' => '',
        'middlewares' => []
    ],
    'GET::/anuncios/store' => [
        'controller' => '',
        'middlewares' => []
    ],
    'GET::/anuncios/update' => [
        'controller' => '',
        'middlewares' => []
    ],
    'GET::/anuncios/delete' => [
        'controller' => '',
        'middlewares' => []
    ],
    'GET::/login' => [
        'controller' => 'Auth\\LoginController::index',
    ],
    'GET::/register' => [
        'controller' => 'RegisterController',
    ],
    'POST::/auth/login' => [
        'controller' => '',
    ],
    'POST::/auth/regiter' => [
        'controller' => '',
    ],
    'POST::/auth/logout' => [
        'controller' => '',
        'middlewares' => []
    ],
];

return $routes;