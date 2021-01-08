<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

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

        // Get an instance of the user model using the ID stored in the session. 
        $userID = Utility\Session::get(Utility\Config::get("SESSION_USER"));
        if (!$User = Model\User::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }        

        if (!$Role = Model\Role::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($userID)->role_name;

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        // Set any dependencies, data and render the view.
        $this->View->addJS("js/register.js");

        // Render view template
        // Usage renderTemplate(string|$template, string|$filepath, array|$data)
        $this->View->renderTemplate($role, "register/index", [
            "title" => "Register",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($userID),
            "users" => Model\User::getUsersInstance($userID)
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
