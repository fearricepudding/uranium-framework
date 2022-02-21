<?php
/**
 * Example
 * Standard Route: "/test" => "exampleController@example"
 * Variable Route: "/test/{variable_name}" => "exampleController@example"
 * The variables are passed as an array to the controller
 */
class routes{
    public static $get_routes=[
        "/" => "exampleController@modelExample",
        "/user" => "exampleController@userexample",
        "/test/{item_test}" => "exampleController@variableExample",
        "/test/{item_test}/test" => "exampleController@variableExtension",
        "/test/{item_test}/test/{seconditem}" => "exampleController@twoVariables"
    ];

    public static $post_routes=[
        "/" => "exampleController@postExample",
        "createUser" => "userController@createUser",
        "searchUser" => "userController@searchUser",
        "updateUser" => "userController@updateUser"
    ];
}
