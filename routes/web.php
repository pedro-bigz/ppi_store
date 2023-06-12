<?php

$routes = [
    'GET::/' => [
        'controller' => 'AnuncioController::index',
    ],
    'POST::/upload' => [
        'controller' => 'FileController::upload',
        'middlewares' => []
    ],
    'GET::/register' => [
        'controller' => '',
    ],
    'GET::/anuncios/:anuncio/show' => [
        'controller' => 'AnuncioController::show',
        'middlewares' => []
    ],
    'GET::/anuncios/create' => [
        'controller' => 'AnuncioController::create',
        'middlewares' => []
    ],
    'POST::/anuncios/store' => [
        'controller' => 'AnuncioController::store',
        'middlewares' => []
    ],
    'GET::/anuncios/:anuncio/edit' => [
        'controller' => 'AnuncioController::edit',
        'middlewares' => []
    ],
    'GET::/anuncios/:anuncio/buy' => [
        'controller' => 'AnuncioController::buy',
        'middlewares' => []
    ],
    'POST::/anuncios/:anuncio/purchase' => [
        'controller' => 'AnuncioController::purchase',
        'middlewares' => []
    ],
    'POST::/anuncios/:anuncio' => [
        'controller' => 'AnuncioController::update',
        'middlewares' => []
    ],
    'DELETE::/anuncios/:anuncio' => [
        'controller' => 'AnuncioController::delete',
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