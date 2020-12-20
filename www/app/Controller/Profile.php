<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

/**
 * Profile Controller:
 *
 * @author John Alex
 * @since 1.0
 */
class Profile extends Core\Controller {

    /**
     * Index: Renders the profile view. NOTE: This controller can only be
     * accessed by unauthenticated users!
     * @access public
     * @example profile/index/{$1}
     * @param string $user [optional]
     * @return void
     * @since 1.0.4
     */
    public function index($user = "") {

        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        // If no user ID has been passed, and a user session exists, display
        // the authenticated users profile.
        if (!$user) {
            $userSession = Utility\Config::get("SESSION_USER");
            if (Utility\Session::exists($userSession)) {
                $user = Utility\Session::get($userSession);
            }
        }

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user);

        if(empty($role->role_name)) {
            Utility\Redirect::to(APP_URL . $role->role_name);
        }

        // Set any dependencies, data and render the view.
        // $this->View->addCSS("css/custom.css");
        // $this->View->addJS("js/custom.js");

        $this->View->renderTemplate($role->role_name, $role->role_name . "/profile/index", [
            "title" => "Profile",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user),
            "role" => $role
        ]);
    }

}
