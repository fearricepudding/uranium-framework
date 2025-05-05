<?php

namespace uranium\cli;

use uranium\core\JobManager;

class System{
    public static function cron(){
        echo "[*] cron running...".PHP_EOL;
        JobManager::runCron();
    }
}
