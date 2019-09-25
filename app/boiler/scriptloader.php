<?php
namespace uranium\boiler;

use uranium\boiler\dbg;

class scriptloader{
	public static function file($FILEPATH = ""){
		if(strlen($FILEPATH) <= 0){
			return false;
		};
		if(file_exists($FILEPATH)){
			require_once($FILEPATH);
			return true;
		}else{
			return false;
		};
	}
	public static function folder($FOLDERPATH = ""){
		if(strlen($FOLDERPATH) <= 0){
			return false;
		};
		if(file_exists($FOLDERPATH)){
			$filesToInclude = scandir($FOLDERPATH);
			foreach($filesToInclude as $file){
				if(!is_dir($FOLDERPATH.'/'.$file)){
					require_once($FOLDERPATH.'/'.$file);
				}
			}
			return true;
		}else{
			return false;
		}
	}
}
