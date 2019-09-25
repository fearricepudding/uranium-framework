<?php
require __DIR__."/app/app.php";

use uranium\boiler\scriptloader;
use uranium\boiler\dbg;
use uranium\bootstrap\router;

// We want to load our libraries
scriptloader::folder(__DIR__."/libs");



router::init();
