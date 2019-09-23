<?php

// Boilerplate
require_once(__DIR__."/boiler/scriptloader.php");

use dark\boiler\scriptloader;

scriptloader::file(__DIR__."/../vendor/autoload.php");

//Load the environment config before any scripts
$dotenv = Dotenv\Dotenv::create(__DIR__.'/../');
$dotenv->load();

// Load the bootstrap
scriptloader::file(__DIR__."/boiler/debug.php");
scriptloader::file(__DIR__.'/../routes.php');
scriptloader::folder(__DIR__."/config");
scriptloader::folder(__DIR__."/bootstrap");
scriptloader::folder(__DIR__."/../controllers");
