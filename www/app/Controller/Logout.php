<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;

/**
 * Logout Controller:
 *
 * @author John Alex
 * @since 1.0.8
 */
class Logout extends Core\Controller {

    /**
     * Index: Renders the login view. NOTE: This controller can only be accessed
     * by unauthenticated users!
     * @access public
     * @example login/index
     * @return void
     * @since 1.0.2
     */
    public function index() {

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
