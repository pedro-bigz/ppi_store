<?php

$routes = [
    'GET::/' => [
        'controller' => 'AnuncioController::index',
    ],
    'POST::/upload' => [
        'controller' => 'FileController::upload',
    ],
    'GET::/register' => [
        'controller' => '',
    ],
    'GET::/anuncios/:anuncio/show' => [
        'controller' => 'AnuncioController::show',
    ],
    'GET::/anuncios/:anuncio/fotos' => [
        'controller' => 'AnuncioController::fotos',
    ],
    'GET::/anuncios/create' => [
        'controller' => 'AnuncioController::create',
        'middlewares' => ['AuthMiddleware']
    ],
    'POST::/anuncios/store' => [
        'controller' => 'AnuncioController::store',
        'middlewares' => ['AuthMiddleware']
    ],
    'GET::/anuncios/:anuncio/edit' => [
        'controller' => 'AnuncioController::edit',
        'middlewares' => ['AuthMiddleware']
    ],
    'POST::/anuncios/:anuncio/purchase' => [
        'controller' => 'AnuncioController::purchase',
    ],
    'POST::/anuncios/:anuncio' => [
        'controller' => 'AnuncioController::update',
    ],
    'DELETE::/anuncios/:anuncio' => [
        'controller' => 'AnuncioController::delete',
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
        'middlewares' => ['AuthMiddleware']
    ],
];

return $routes;