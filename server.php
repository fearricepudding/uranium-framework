<?php
require __DIR__."/app/app.php";

use uranium\scriptloader;
use uranium\core\router;

// We want to load our libraries
scriptloader::folder(__DIR__."/scripts");



router::init();
