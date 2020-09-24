<?php

// Config
define("ROOT", realpath(dirname(__FILE__) . "/../") . "/");

// App Config
define("APP_NAME", "A2B Hub");
define("APP_ROOT", ROOT . "App/");
define("APP_PROTOCOL", stripos($_SERVER["SERVER_PROTOCOL"], "https") === true ? "https://" : "http://");
define("APP_URL", APP_PROTOCOL . $_SERVER["HTTP_HOST"] . str_replace("public", "", dirname($_SERVER["SCRIPT_NAME"])) . "");
define("APP_CONFIG_FILE", "config.php");

// Public Config
define("PUBLIC_ROOT", ROOT . "public");

// Controller Config
define("CONTROLLER_PATH", "\App\Controller\\");
define("DEFAULT_CONTROLLER", CONTROLLER_PATH . "Index");
define("DEFAULT_CONTROLLER_ACTION", "index");
