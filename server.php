<?php
require __DIR__."/app/app.php";

use dark\boiler\scriptloader;
use dark\boiler\dbg;
use dark\bootstrap\router;

// We want to load our libraries
scriptloader::folder(__DIR__."/libs");
router::init();
