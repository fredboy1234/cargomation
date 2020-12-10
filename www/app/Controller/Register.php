<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;

/**
 * Register Controller:
 *
 * @author John Alex
 * @since 1.0.2
 */
class Register extends Core\Controller {

    /**
     * Index: Renders the register view. NOTE: This controller can only be
     * accessed by unauthenticated users!
     * @access public
     * @example register/index
     * @return void
     * @since 1.0.2
     */
    public function index() {
        
        // Check that the user is unauthenticated.
        Utility\Auth::checkAuthenticated();

        // Set any dependencies, data and render the view.
        $this->View->renderTemplate("admin", "register/index", [
            "title" => "Register"
        ]);
    }

    /**
     * Register: Processes a create account request. NOTE: This controller can
     * only be accessed by unauthenticated users!
     * @access public
     * @example register/_register
     * @return void
     * @since 1.0.2
     */
    public function _register() {
       
        // Check that the user is unauthenticated.
        //Utility\Auth::checkUnauthenticated();

        $accId = Utility\Session::get(Utility\Config::get("SESSION_USER"));

        // Process the register request, redirecting to the login controller if
        // successful or back to the register controller if not.
        if (Model\UserRegister::register($accId)) { 
            Utility\Redirect::to(APP_URL . "login");
        }
        Utility\Redirect::to(APP_URL . "register");
    }

}
