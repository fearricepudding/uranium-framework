<?php

namespace uranium\core; 

use uranium\core\RouteRegister;
use uranium\core\Route;

// Middlewares
use uranium\middleware\CheckAuthentication;
use uranium\middleware\CheckCSRF;
use uranium\middleware\RequireLogin;

class routes extends RouteRegister{

    public function __construct(){
        // Loggedin dashboard
        $this->register($this->get("/", "pageController@index"));
        $this->register($this->get("/dashboard", "pageController@dashboard")
                ->middleware(RequireLogin::class));
        
        // Login
        $this->register($this->get("/login", "pageController@login")
                ->middleware(CheckAuthentication::class));
        $this->register($this->post("/login", "userController@loginUser")
                ->middleware(CheckCSRF::class)
                ->middleware(CheckAuthentication::class));  

        // Register
        $this->register($this->get("/register", "pageController@register")
                ->middleware(CheckAuthentication::class));
        $this->register($this->post("/register", "userController@createUser")
                ->middleware(CheckCSRF::class)
                ->middleware(CheckAuthentication::class));

        // Logout
        $this->register($this->get("/logout", "userController@destroySession"));

    }
}
