<?php
require __DIR__."/app/app.php";

use uranium\scriptloader;
use uranium\core\router;

scriptloader::folder(__DIR__."/scripts");
router::init();
