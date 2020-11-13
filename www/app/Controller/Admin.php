<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

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
        Utility\Auth::checkAuthenticated();

        // Get an instance of the user model using the ID stored in the session. 
        $userID = Utility\Session::get(Utility\Config::get("SESSION_USER"));
        if (!$User = Model\User::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }

        if(Model\Role::isAdmin($User)) {
            
            // $arr = $this->View->loadResouces(PUBLIC_ROOT . "/" . "/bower_components/");
            // var_dump($arr);

            // Set any dependencies, data and render the view.
            // $dir = PUBLIC_ROOT . "/bower_components/";

            // $css_files = Utility\Scan::scanDIR($dir, ["css", "min.css"], true); 
            // $this->View->loadCSS($css_files);

            // $js_files = Utility\Scan::scanDIR($dir, ["js", "min.js"], true); 
            // $this->View->loadCSS($js_files);
            // $this->View->addCSS("css/google_font.css");
            // $this->View->addCSS("css/custom.css");
            // $this->View->addJS("js/custom.js");

            // Render view template
            // Usage renderTemplate(string|$template, string|$filepath, array|$data)
            $this->View->renderTemplate("admin", "admin/index", [
                "title" => "Dashboard",
                "data" => (new Presenter\Profile($User->data()))->present(),
                "users" => Model\User::getUsersInstance()
            ]);
        } else {
            Utility\Redirect::to(APP_URL);
        }

        // $this->View->renderTemplateTwig("/admin/index.php", [
        //     "title" => "Admin"
        // ]);
    }

    /**
     * Profile: Renders the profile view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function profile($user = "") {

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

        // Set any dependencies, data and render the view.
        // $this->initExternals();
        // $this->View->addCSS("css/google_font.css");
        // $this->View->addCSS("css/custom.css");
        // $this->View->addJS("js/custom.js");

        $this->View->renderTemplate("admin", "/admin/profile/index", [
            "title" => "Profile",
            "data" => (new Presenter\Profile($User->data()))->present()
        ]);
    }

    /**
     * Shipment: Renders the shipment view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function shipment($user = "") {

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

        // // Get an instance of the user model using the user ID passed to the
        // // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        if (!$Shipment = Model\Shipment::getInstance()) {
            Utility\Redirect::to(APP_URL);
        }

        $shipment_id = $Shipment->getShipment($user, "shipment_num");

        $Document = Model\Document::getInstance();

        // Set any dependencies, data and render the view.
        // $this->initExternals();
        // $this->View->addCSS("css/google_font.css");
        // $this->View->addCSS("css/custom.css");
        // $this->View->addJS("js/custom.js");

        $this->View->renderTemplate("admin", "/admin/shipment/index", [
            "title" => "Shipment",
            "shipment" => $Shipment->getShipment($user),
            "document" => $Document->getDocument($shipment_id)
        ]);
    }

    /**
     * Transport: Renders the transport view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function transport($shipment_id = "") {

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

        // Set any dependencies, data and render the view.
        // $this->initExternals();
        // $this->View->addCSS("css/google_font.css");
        // $this->View->addCSS("css/custom.css");
        // $this->View->addJS("js/custom.js");

        $this->View->renderTemplate("admin", "/admin/transport/index", [
            "title" => "Transport",
            // "data" => (new Presenter\Profile($User->data()))->present()
        ]);
    }

    /**
     * Log: Renders the log view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function log(){

        // $this->initExternals();
        // $this->View->addCSS("css/google_font.css");
        // $this->View->addCSS("css/custom.css");
        // $this->View->addJS("js/custom.js");

        $this->View->renderTemplate("admin", "/admin/logs/index", [
            "title" => "User Logs"
        ]);
    }

    /**
     * Document: Renders the document view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function document($shipment_id = "", $type = "", $user = "") {


        //$api_url = "http://a2bfreighthub.com/eAdaptor/jsoneAdaptor.php?shipment_id=" . $shipment_id . "&request=document";

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

        // // Get an instance of the user model using the user ID passed to the
        // // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }
        
        // if (!$Shipment = Model\Shipment::getInstance()) {
        //     Utility\Redirect::to(APP_URL);
        // }

        $Document = Model\Document::getInstance();

        $this->View->render("admin/document", [
            "title" => "Shipment API",
            "data" => ["shipment_id" => $shipment_id, "type" => $type], 
            "document" => $Document->getDocument($shipment_id, $type)
        ]);
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
