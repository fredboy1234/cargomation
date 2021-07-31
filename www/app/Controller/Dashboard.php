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

        // assign value by user role
        switch ($role->role_id) {
            case 1: // SUPER ADMIN
                $is_customer = false;
                break;
            case 2: // CLIENT ADMIN
                $is_customer = false;
                break;
            case 3: // STAFF
                $is_customer = false;
                break;
            case 4: // CUSTOMER
                $org_code = Model\User::getUserInfoByID($userID)[0]->organization_code;
                $is_customer = $org_code;
                break;
            
            default:
                die('Error in getting the user role');
                break;
        }

        // // User Log
        // $User->putUserLog([
        //     "log_id" => 1,
        //     "user_id" => $userID,
        //     "role_id" => $role->role_id,
        //     "login_time" => date("Y-m-d H:i:s"),
        //     "ip_address" => $User->getIPAddress(),
        //     "duration" => "-",
        //     "log_type" => "redirect",
        //     "log_action" => "Access dashboard",
        // ]);


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

        $cmode = Model\Shipment::getShipmentDynamic($userID,'container_mode, transport_mode', 'containermode');
        $cmodeArray = array();
        $seacount = 0;
        $aircount =0;
        foreach($cmode as $value){
            if($value->transport_mode === 'Sea'){
                $cmodeArray['sea'][$value->container_mode][] = $value;
                $seacount++;
            }else{
                $cmodeArray['air'][$value->container_mode][] = $value;
                $aircount++;
            }
           
        }
            // echo"<pre>";
            // print_r($seacount .''. $aircount);
            // exit;
        
        $this->View->renderTemplate("/dashboard", [
            "title" => "Dashboard",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "notifications" => Model\User::getUserNotifications($userID),
            "user" => (Object) Model\User::getProfile($userID),
            "users" => Model\User::getUsersInstance($userID),
            "menu" => Model\User::getUserMenu($role->role_id),
            "image_profile" => $profileImage,
            "dash_photo" => Model\User::getUsersDashPhoto($userID),
            "selected_theme" => $selectedTheme,
            "role" => $role,
            "total_shipment" => count(Model\Shipment::getShipmentDynamic($userID, 'user_id', '', $is_customer)),
            "not_arrived" => count(Model\Shipment::getShipmentDynamic($userID,'user_id', 'not arrived', $is_customer)),
            "air_shipment" => count(Model\Shipment::getShipmentDynamic($userID,'user_id', 'air', $is_customer)),
            "sea_shipment" => count(Model\Shipment::getShipmentDynamic($userID,'user_id', 'sea', $is_customer)),
            "shipment_with_port" => json_encode(Model\Shipment::getShipmentDynamic($userID,'*', 'port')),
            "port_loading_count" => json_encode(Model\Shipment::countOfPort($userID)),
            "document_stats" => Model\Document::getDocumentStats($userID),
            "container_mode" => $cmodeArray,
            "count_cmode" => count($cmode),
            "count_sea" => $seacount,
            "count_air" => $aircount,
        ]);
        $this->externalTemp();
    }

    public function externalTemp(){
        echo '<link rel="stylesheet" href="https://turbo87.github.io/leaflet-sidebar/src/L.Control.Sidebar.css" crossorigin=""/>';
        //  echo '<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        //  integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        //  crossorigin=""></script>';
        //echo '<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>';
         //echo '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>';
        // echo '<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>';
        //echo '<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>';
        //  echo '<script src="https://unpkg.com/leaflet/dist/leaflet-src.js"></script>';
        //  echo '<script src="https://unpkg.com/esri-leaflet"></script>';
        //echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/esri-leaflet-geocoder/3.0.0/esri-leaflet-geocoder.js"></script>';
        //echo '<script src="https://turbo87.github.io/leaflet-sidebar/src/L.Control.Sidebar.js"></script>';
        echo ' <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>';
        echo ' <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>';
    }

}