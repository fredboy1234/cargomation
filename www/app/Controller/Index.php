<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;

/**
 * Index Controller:
 *
 * @author John Alex
 * @since 1.0
 */
class Index extends Core\Controller {

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

        if (!$Role = Model\Role::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($userID)->role_name;

        if(!empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        Utility\Redirect::to('/404.php');

        $selectedTheme = $User->getUserSettings($userID);
        if(isset($selectedTheme[0])){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = '';
        }
        
        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        $this->View->addCSS("css/".$selectedTheme.".css");
        // Set any dependencies, data and render the view.
        // $this->View->addCSS("dist/css/index.css");
        // $this->View->addJS("dist/js/index.jquery.js");
        // $this->View->render("index/index", [
        //     "title" => "Index",
        //     "user" => $User->data()
        // ]);
    }

}
