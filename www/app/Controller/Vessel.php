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
class Vessel extends Core\Controller {

    public function __construct($requestMethod = '', $key = '', $value = '', $param = []) {
        // Create a new instance of the model shipment class.
        $this->Vessel = Model\Vessel::getInstance();
        // Create a new instance of the model document class.
        $this->Document = Model\Document::getInstance();
        // Create a new instance of the core view class.
        $this->View = new Core\View;

        $this->requestMethod = $requestMethod;
        $this->param = $param;
        $this->value = $value;
        $this->key = $key;
    }

    /**
     * Index: Renders the index view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
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

        // // Get an instance of the user model using the user ID passed to the
        // // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }
        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }
        $role = $Role->getUserRole($user)->role_name;

      
        if($role == 'user'){
            $shipment_id = $this->Shipment->getClientUserShipment($user, "shipment_num");
        }
       
        $role = $Role->getUserRole($user)->role_name;

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        
        $selectedTheme = $User->getUserSettings($user);
        if(isset($selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = '';
        }
        
        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        $this->View->addCSS("css/vessel.css");
        $this->View->addJS("js/vessel.js");
        
        $imageList = (Object) Model\User::getProfile($user);
        $profileImage = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }
       
        $this->View->renderTemplate($role, $role . "/vessel/index", [
            "title" => "Vessel Track",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user),
            "vessel" => $this->Vessel->getVessel($user),
            "image_profile" => $profileImage,
            'selected_theme' => $selectedTheme,
            'role' => $role,
            'mapToken' => 'pk.eyJ1IjoidGl5bzE0IiwiYSI6ImNrbTA1YzdrZTFmdGIyd3J6OXFhbHcyYTEifQ.R2vfZbgOCPtFG6lgAMWj7A'
        ]);

        $this->externalTemp();

    }

    public function details($user=""){

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
         if (!$Role = Model\Role::getInstance($user)) {
             Utility\Redirect::to(APP_URL);
         }
         $role = $Role->getUserRole($user)->role_name;

         $uri = explode( '/', $_SERVER['REQUEST_URI'] );
         $vessel_number = '';
         if(isset($uri[2])){
            $vessel_number = explode('?',$uri[2]);
         }
        
         $selectedTheme = $User->getUserSettings($user);
        if(isset($selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = '';
        }
        
        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        $this->View->addCSS("css/vessel.css");
        $this->View->addJS("js/vessel.js");
        
        $imageList = (Object) Model\User::getProfile($user);
        $profileImage = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }

        $vessel_number = (isset($vessel_number[1])? $vessel_number[1] :'');
        
        $this->View->addJS("js/vessel.js");
        $this->View->renderTemplate($role, $role . "/vessel/details", [
            "title" => "Vessel Track",
            "vesseldata" => $this->Vessel->getVesselByNumber($vessel_number,$user),
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user),
            "image_profile" => $profileImage,
            'selected_theme' => $selectedTheme,
            'role' => $role,
            'mapToken' => 'pk.eyJ1IjoidGl5bzE0IiwiYSI6ImNrbTA1YzdrZTFmdGIyd3J6OXFhbHcyYTEifQ.R2vfZbgOCPtFG6lgAMWj7A',
        ]);
    }

    public function vesselSSR($user=""){
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
        $vessel = $this->Vessel->getVessel($user);
        $data =array();
        $color = array();
        $store = array();
        if(!empty($vessel)){
            foreach($vessel as $ves){
                $dateTrack = date_create($ves->date_track);
                $day = date_format($dateTrack,"l");
                $month = date_format($dateTrack,"F j,Y");
                $hour = date_format($dateTrack,'h:i:s A');
                
                $link = '<a class="col-sm-3 dcontent" href="/vessel/details?'.$ves->container_number.'">'.$ves->container_number.'</a>';
                $subdata =array(); 
                $subdata['container_number'] = $link;
                $subdata['vessel_name'] = $ves->vessel;
                $subdata['location_city'] = '<p class="loc-city">'.$ves->location_city.'</p>';
                $subdata['date_track'] = $month." - ".$hour;
                $subdata['status'] = $ves->moves;
                $subdata['voyage'] = $ves->voyage;
                
                $data[] = $subdata; 
            }
           
            $json_data=array(
                "data"  =>  $data,
            );
           
            
        }
        echo json_encode($json_data);
    }

    //temporary only please ignore
    public function externalTemp(){
        //  echo '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        //  integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        //  crossorigin=""/>';
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
        
    }
}