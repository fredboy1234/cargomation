<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;

/**
 * Login Controller:
 *
 * @author John Alex
 * @since 1.0.2
 */
class Login extends Core\Controller {

    /**
     * Index: Renders the login view. NOTE: This controller can only be accessed
     * by unauthenticated users!
     * @access public
     * @example login/index
     * @return void
     * @since 1.0.2
     */
    public function index() {

        // Check that the user is unauthenticated.
        Utility\Auth::checkUnauthenticated();

        // // Set any dependencies, data and render the view.
        $this->View->addCSS("bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css");
        $this->View->addCSS("bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css");
        $this->View->addCSS("bower_components/admin-lte/dist/css/adminlte.css");
        $this->View->addCSS("css/google_font.css");
        $this->View->addCSS("css/custom.css");

        // <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        $this->View->addJS("bower_components/admin-lte/plugins/jquery/jquery.min.js");
        $this->View->addJS("js/index.jquery.js");

        // Set any dependencies, data and render the view.
        $this->View->render("login/index", [
            "title" => "Log In"
        ]);

    }

    /**
     * Login: Processes a login request. NOTE: This controller can only be
     * accessed by unauthenticated users!
     * @access public
     * @example login/_login
     * @return void
     * @since 1.0.2
     */
    public function _login() {

        

        // Check that the user is unauthenticated.
        Utility\Auth::checkUnauthenticated();

        //var_dump($_POST); die();

        // Process the login request, redirecting to the home controller if
        // successful or back to the login controller if not.
        if (Model\UserLogin::login()) {
            Utility\Redirect::to(APP_URL);
        }
        echo 'false';
        // Utility\Redirect::to(APP_URL . "login");
    }

    /**
     * Login With Cookie: Processes a login with cookie request. NOTE: This
     * controller can only be accessed by unauthenticated users!
     * @access public
     * @example login/_loginWithCookie
     * @return void
     * @since 1.0.3
     */
    public function _loginWithCookie() {

        // Check that the user is unauthenticated.
        Utility\Auth::checkUnauthenticated();

        // Process the login with cookie request, redirecting to the home
        // controller if successful or back to the login controller if not.
        if (Model\UserLogin::loginWithCookie()) {
            Utility\Redirect::to(APP_URL);
        }
        Utility\Redirect::to(APP_URL . "login");
    }

    /**
     * Logout: Processes a logout request. NOTE: This controller can only be
     * accessed by authenticated users!
     * @access public
     * @example login/logout
     * @return void
     * @since 1.0.2
     */
    public function logout() {

        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        // Process the logout request, redirecting to the login controller if
        // successful or to the default controller if not.
        if (Model\UserLogin::logout()) {
            Utility\Redirect::to(APP_URL . "login");
        }
        Utility\Redirect::to(APP_URL);
    }

}
