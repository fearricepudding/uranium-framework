<?php

require_once __DIR__."/app/app.php";

use uranium\ScriptLoader;
use uranium\core\Router;
use uranium\core\ErrorHandler;
use uranium\core\ConfigHandler;

register_shutdown_function(array('uranium\core\ErrorHandler', 'check_for_fatal'));
set_error_handler(array('uranium\core\ErrorHandler', 'log_error'));

ini_set("display_errors", "off");
//error_reporting(E_ALL);

ScriptLoader::folder(__DIR__."/scripts");

@session_start();
router::init();
