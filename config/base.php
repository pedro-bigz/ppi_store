<?php

session_start();
ob_start();

define("CONTROLLERS_PATH", "App\\Controllers");
define("ERROR_VIEW_PATH", __DIR__."/../resources/errors");
define('ROOT_PATH', __DIR__."/..");