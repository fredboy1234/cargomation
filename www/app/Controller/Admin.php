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
class Admin extends Core\Controller {

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
        // Utility\Auth::checkAuthenticated();

        // Get an instance of the user model using the ID stored in the session. 
        // $userID = Utility\Session::get(Utility\Config::get("SESSION_USER"));
        // if (!$User = Model\User::getInstance($userID)) {
        //     Utility\Redirect::to(APP_URL);
        // }

        // if(Model\Role::isAdmin($User)) {
            
            // Set any dependencies, data and render the view.
            $this->View->addCSS("css/google_font.css");
            $this->View->addCSS("css/custom.css");
            $this->View->addJS("js/custom.js");
        
            // Render admin view
            // $this->View->renderTemplate("admin", "admin/index2", [
            //     "title" => "Admin"
            //     // "user" => $User->data()
            // ]);
        // } else {
        //     Utility\Redirect::to(APP_URL);
        // }

        $this->View->render("admin/index2", [
            "title" => "Login"
        ]);
    }

    public function initVar() {
        $this->View->addCSS("css/google_font.css");
        $this->View->addCSS("css/custom.css");
        $this->View->addJS("js/custom.js");
    }

    // public function isAuthorized($User){
    //     $id = $User->data()->role;
    //     $role = Model\Role::getRole($id);

    //     if(isset($role) && $role == 'admin'){
    //         return true;
    //     }
    //     return false;
    // }
}
