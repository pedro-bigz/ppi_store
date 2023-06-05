<?php

require_once __DIR__.'/database.php';

session_start();
ob_start();

define("DEBUG", true);

define("CONTROLLERS_PATH", "App\\Controllers");
define("VIEW_PATH", __DIR__."/../resources/views");
define("ERROR_VIEW_PATH", __DIR__."/../resources/errors");
define("ROOT_PATH", __DIR__."/..");

define("APP_NAME", "PPI_STORE");
define("SERVER_HOST", "localhost");
define("SERVER_ADDR", "127.0.0.1");
define("SERVER_PORT", "80");
define("SERVER_PROTOCOL", "HTTP/1.1");