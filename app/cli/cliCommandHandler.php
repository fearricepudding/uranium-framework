<?php

class cliCommandHandler{
	public function __construct($argv){
		$cmd = "";
		unset($argv[0]);
		foreach($argv as $cmdline){
			$cmd .= $cmdline;
		}
		echo "Running command: ".$cmd.PHP_EOL;
	}
}