<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;

use App\Core\View;

/**
 * Index Controller:
 *
 * @author John Alex
 * @since 1.0
 */
class Client extends Core\Controller {

    /**
     * Index: Renders the index view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function index() {

        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        // Get an instance of the user model using the ID stored in the session. 
        $userID = Utility\Session::get(Utility\Config::get("SESSION_USER"));
        if (!$User = Model\User::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }
 
        // Set any dependencies, data and render the view.
        $this->View->addCSS("css/google_font.css");
        $this->View->addCSS("css/custom.css");
        $this->View->addJS("js/custom.js");

        $this->View->render("client/index", [
            "title" => "Index",
            "user" => $User->data()
        ]);

        //Core\View::renderTemplate('client/index.html');
    }

}
