<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

/**
 * User Controller:
 *
 * @author John Alex
 * @since 1.0
 */
class User extends Core\Controller {

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

        Utility\Redirect::to('/dashboard');

        // $this->View->renderTemplateTwig("/user/index.php", [
        //     "title" => "user"
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
        // $this->View->addCSS("css/custom.css");

        $this->View->renderTemplate("user", "/user/profile/index", [
            "title" => "Profile",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user)
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
        $this->View->addCSS("css/shipment.css");
        $this->View->addJS("js/shipment.js");

        $docsCollection =array();
        foreach($Document->getDocumentByShipment($shipment_id) as $key=>$value){
            $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        }

        $this->View->renderTemplate("user", "/user/shipment/index", [
            "title" => "Shipment",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "shipment" => $Shipment->getShipment($user),
            "document" => $Document->getDocumentByShipment($shipment_id),
            "document_per_type" => $docsCollection,
            "child_user" => Model\User::getUsersInstance($user),
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

        $this->View->renderTemplate("user", "/user/transport/index", [
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

        $this->View->renderTemplate("user", "/user/logs/index", [
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
    public function document($shipment_id = "", $type = "", $user_id = "") {

        //$api_url = "http://a2bfreighthub.com/eAdaptor/jsoneAdaptor.php?shipment_id=" . $shipment_id . "&request=document";

        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        // If no user ID has been passed, and a user session exists, display
        // the authenticated users profile.
        if (!$user_id) {
            $userSession = Utility\Config::get("SESSION_USER");
            if (Utility\Session::exists($userSession)) {
                $user_id = Utility\Session::get($userSession);
            }
        }

        // // Get an instance of the user model using the user ID passed to the
        // // controll action. 
        if (!$User = Model\User::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }
        
        // if (!$Shipment = Model\Shipment::getInstance()) {
        //     Utility\Redirect::to(APP_URL);
        // }

        $Document = Model\Document::getInstance();

        $this->View->addJS("js/document.js");
        $this->View->addCSS("css/document.css");

        $this->View->render("user/document/index", [
            "title" => "Shipment API",
            "id" => $User->data()->id,
            "email" => $User->data()->email,
            "shipment" => ["shipment_id" => $shipment_id, "type" => $type], 
            "document" => $Document->getDocumentByShipment($shipment_id, $type),
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

    public function fileviewer($user = "", $document_id){ 

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

        $this->View->render("user/document/fileviewer", [
            "email" => $User->data()->email,
            "document_id" => $document_id,
        ]);
    }

    public function curl_get_contents($url) {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
        $ch = curl_init($protocol . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}