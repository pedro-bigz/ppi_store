<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../config/base.php';

$app = Core\Application\App::singleton(
    Core\Routing\Router::make()
);
$app->run();