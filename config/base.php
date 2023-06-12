<?php

require_once __DIR__.'/database.php';

session_start();
ob_start();

define("DEBUG", true);

define("ROOT_PATH", "/var/www/ufu/PPI_STORE");

define("CONTROLLERS_NAMESPACE", "App\\Controllers");
define("MODELS_NAMESPACE", "App\\Models");

define("VIEW_PATH", ROOT_PATH."/resources/views");
define("ERROR_VIEW_PATH", ROOT_PATH."/resources/errors");
define("FILE_UPLOAD_PATH", ROOT_PATH."/public/images");

define("APP_NAME", "Big Adverts");
define("APP_AGENT", "PPI_STORE");
define("SERVER_HOST", "localhost");
define("SERVER_ADDR", "127.0.0.1");
define("SERVER_PORT", "80");
define("SERVER_PROTOCOL", "HTTP/1.1");