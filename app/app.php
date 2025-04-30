<?php

require_once(__DIR__."/uranium/ScriptLoader.php");

use uranium\ScriptLoader;

$_SERVER['URANIUM_ENV_CONFIG_PATH'] = __DIR__."/../.env";

ScriptLoader::folder(__DIR__."/utils");
ScriptLoader::file(__DIR__."/uranium/Route.php");
ScriptLoader::file(__DIR__."/uranium/RouteRegister.php");
ScriptLoader::file(__DIR__."/../routes.php");
ScriptLoader::folder(__DIR__."/uranium");
ScriptLoader::folder(__DIR__."/../models", true);
ScriptLoader::folder(__DIR__."/component", true);
ScriptLoader::folder(__DIR__."/middleware", true);
ScriptLoader::folder(__DIR__."/../controllers", true);
ScriptLoader::folder(__DIR__."/cli", true);
