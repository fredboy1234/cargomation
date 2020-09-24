<?php

namespace Utility;

/**
 * Authentication
 *
 * PHP version 7.0
 */
class Auth 
{

    /**
     * Check Authenticated: Checks to see if the user is authenticated,
     * destroying the session and redirecting to a specific location if the user
     * session doesn't exist.
     * 
     * @param string $redirect
     * 
     * @return void
     */
    public static function checkAuthenticated($redirect = "login") {
        Session::init();
        if (!Session::exists(Config::get("SESSION_USER"))) {
            Session::destroy();
            Redirect::to(APP_URL . $redirect);
        }
    }

    /**
     * Check Unauthenticated: Checks to see if the user is unauthenticated,
     * redirecting to a specific location if the user session exist.
     * 
     * @param string $redirect
     * 
     * @return void
     */
    public static function checkUnauthenticated($redirect = "") {
        Session::init();
        if (Session::exists(Config::get("SESSION_USER"))) {
            Redirect::to(APP_URL . $redirect);
        }
    }

}
