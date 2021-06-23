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

        $role = $Role->getUserRole($userID);

        // var_dump(Model\User::getUserNotifications($userID));
        //  die();

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        $selectedTheme = $User->getUserSettings($userID);

        if(isset($selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = 'default';
        }

        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        $this->View->addCSS("css/".$selectedTheme.".css");
        $this->View->addJS("js/dashboard.js");
        
        // Render view template
        // Usage renderTemplate(string|$template, string|$filepath, array|$data)

        $imageList = (Object) Model\User::getProfile($userID);
        $profileImage = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }
        
        $this->View->renderTemplate("/dashboard", [
            "title" => "Dashboard",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "notifications" => Model\User::getUserNotifications($userID),
            "user" => (Object) Model\User::getProfile($userID),
            "users" => Model\User::getUsersInstance($userID),
            "image_profile" => $profileImage,
            "dash_photo" => Model\User::getUsersDashPhoto($userID),
            "selected_theme" => $selectedTheme,
            "role" => $role,
            "total_shipment" => count(Model\Shipment::getShipmentDynamic($userID)),
            "not_arrived" => count(Model\Shipment::getShipmentDynamic($userID,'user_id', 'not arrived')),
            "air_shipment" => count(Model\Shipment::getShipmentDynamic($userID,'user_id', 'air')),
            "sea_shipment" => count(Model\Shipment::getShipmentDynamic($userID,'user_id', 'sea')),
        ]);
    }

}