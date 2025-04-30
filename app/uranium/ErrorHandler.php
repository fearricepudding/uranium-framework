<?php

namespace uranium\core;

class ErrorHandler{
    public static function log_error( $num, $str, $file, $line, $context = null ){
        echo "<h2>Log error</h2>";
        var_dump($num);
        var_dump($str);
        var_dump($file);
        var_dump($line);
        exit();
    }

	public static function log_exception($num){
		self::handleError(new \ErrorException($str, 0, $num, $file, $line));
        exit();
	}
   
    public static function check_for_fatal(){
        $error = error_get_last();

        // TODO: disable verbose errors in .env
        $verbose = true;
        
        if($error){
            echo "<h2>Fatal error</h2>";
            if($verbose){
                echo "<h3>".$error["file"]." : ".$error["line"]."</h3>";
                $components = explode("Stack trace:", $error["message"]);
                echo "<p>".$components[0]."</p>";

                $stacks = explode("#", $components[1]);
                foreach($stacks as $stack){
                    echo "<p> #".$stack."</p>";
                };

                //var_dump($error);
                // Show error screen with error context
                //
            };
            exit();
        };
    }
};
