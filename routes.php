<?php
/**
 * Example
 * Standard Route: "/test" => "exampleController@example"
 * Variable Route: "/test/{variable_name}" => "exampleController@example"
 * The variables are passed as an array to the controller
 */
class routes{
    public static $public_routes=[
        "/" => "exampleController@examplepage",
    	"/user" => "exampleController@userexample",
		"/test/{item_test}" => "exampleController@variableExample",
		"/test/{item_test}/test" => "exampleController@variableExtension",
		"/test/{item_test}/test/{second_item}" => "exampleController@twoVariables"
    ];
}
