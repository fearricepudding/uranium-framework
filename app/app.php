<?php

require_once(__DIR__."/scriptloader.php");

use uranium\scriptloader;

scriptloader::file(__DIR__."/../env.php");
scriptloader::file(__DIR__."/../routes.php");
scriptloader::folder(__DIR__."/core");
scriptloader::folder(__DIR__."/cli");
scriptloader::folder(__DIR__."/scripts");
scriptloader::folder(__DIR__."/../models");
scriptloader::folder(__DIR__."/../controllers");
