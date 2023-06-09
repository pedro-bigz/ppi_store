<?php

$routes = [
    'GET::/' => [
        'controller' => 'AnuncioController::index',
    ],
    'GET::/register' => [
        'controller' => '',
    ],
    'GET::/anuncios' => [
        'controller' => '',
        'middlewares' => []
    ],
    'GET::/anuncios/:anuncio' => [
        'controller' => 'AnuncioController::show',
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
        'controller' => 'AnuncianteController::login',
    ],
    'GET::/register' => [
        'controller' => 'AnuncianteController::register',
    ],
    'POST::/auth/login' => [
        'controller' => 'Auth\\AuthController::login',
    ],
    'POST::/auth/register' => [
        'controller' => 'Auth\\AuthController::register',
    ],
    'DELETE::/auth/logout' => [
        'controller' => 'Auth\\AuthController::delete',
        'middlewares' => []
    ],
];

return $routes;