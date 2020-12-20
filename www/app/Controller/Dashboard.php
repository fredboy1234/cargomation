<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

/**
 * Index Controller:
 *
 * @author John Alex
 * @since 1.0
 */
class Dashboard extends Core\Controller {

    /**
     * Index: Renders the index view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function index() {

        // Get an instance of the user model using the ID stored in the session. 
        $userID = Utility\Session::get(Utility\Config::get("SESSION_USER"));
        if (!$User = Model\User::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }
        // $arr = $this->View->loadResouces(PUBLIC_ROOT . "/" . "/bower_components/");
        // var_dump($arr);

        // Set any dependencies, data and render the view.
        // $dir = PUBLIC_ROOT . "/bower_components/";
        // $css_files = Utility\Scan::scanDIR($dir, ["css", "min.css"], true); 
        // $this->View->loadCSS($css_files);
        // $js_files = Utility\Scan::scanDIR($dir, ["js", "min.js"], true); 
        // Set any dependencies, data and render the view.
        // $this->View->loadCSS($js_files);
        // $this->View->addCSS("css/google_font.css");
        // $this->View->addCSS("css/custom.css");
        // $this->View->addJS("js/custom.js");
        

        if (!$Role = Model\Role::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($userID)->role_name;

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }


        // Render view template
        // Usage renderTemplate(string|$template, string|$filepath, array|$data)
        $this->View->renderTemplate($role, $role . "/dashboard", [
            "title" => "Dashboard",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "users" => Model\User::getUsersInstance($userID)
        ]);
    }

}