<?php

// Boilerplate
require_once(__DIR__."/scriptloader.php");

use uranium\scriptloader;

scriptloader::file(__DIR__."/../vendor/autoload.php");

//Load the environment config before any scripts
// $dotenv = Dotenv\Dotenv::create(__DIR__.'/../');
// $dotenv->load();
// $dotenv->required(['SALT', 'COST']);

// Load the bootstrap
scriptloader::file(__DIR__.'/../routes.php');
scriptloader::folder(__DIR__."/database");
scriptloader::folder(__DIR__."/core");
scriptloader::folder(__DIR__."/cli");
scriptloader::folder(__DIR__."/../models");
scriptloader::folder(__DIR__."/../controllers");
