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
            'mapToken' => 'pk.eyJ1IjoidGl5bzE0IiwiYSI6ImNrbTA1YzdrZTFmdGIyd3J6OXFhbHcyYTEifQ.R2vfZbgOCPtFG6lgAMWj7A',
            "notifications" => Model\User::getUserNotifications($user)
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
        $color_code_vessel = array();
        $t=0;
        $c_flag=array();
        $vesseeldata = $this->Vessel->getVesselByNumber($vessel_number,$user);

        foreach($vesseeldata as $ot){
            // if($t==0){
            //     $ot->date_track = '2021-05-28 11:31:00';
            // }
            if(strtotime($ot->date_track) < strtotime(date("Y-m-d h:i:s"))){
                $color_code_vessel['before'][] =  $ot;
            }else{
                $color_code_vessel['after'][] = $ot;
            } 
           
            $port_array = $this->Vessel->getSeaPort($ot->location_city);
            if(!empty($port_array)){
                $country = explode(",",$port_array[0]->port_name);
                $country_flag = $this->getFlag(trim(end($country)));
                $c_index = preg_replace('/\s*/', '', $ot->location_city);
                $c_flag[strtolower($c_index)][]=json_decode($country_flag)[0]->flag;
                
            }
           
            $t++;        
        }
        $searates  = 'empty';
        if(!isset($_SESSION['vesselnum'])){
            $_SESSION['vesselnum'] = '';
        }
        
        //$_SESSION['livesearates'] = '';

        //if( $_SESSION['livesearates'] = ""  ||  $_SESSION['vesselnum'] != $vessel_number ){
            $searates = file_get_contents('https://tracking.searates.com/route?type=CT&number='.$vessel_number.'&sealine=ANNU&api_key=OEHZ-7YIN-1P9R-T8X4-F632');
            $_SESSION['livesearates'] =  $searates;
            $_SESSION['vesselnum'] =$vessel_number;
        //}

        
        
        $this->View->addJS("js/vessel.js");
        $this->View->renderTemplate($role, $role . "/vessel/details", [
            "title" => "Vessel Track",
            "vesseldata" => $vesseeldata,
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user),
            "image_profile" => $profileImage,
            'selected_theme' => $selectedTheme,
            'role' => $role,
            'mapToken' => 'pk.eyJ1IjoidGl5bzE0IiwiYSI6ImNrbTA1YzdrZTFmdGIyd3J6OXFhbHcyYTEifQ.R2vfZbgOCPtFG6lgAMWj7A',
            'geocodeToken' => 'pk.fe49a0fae5b7f62ed12a17d8c2a77691',
            "notifications" => Model\User::getUserNotifications($user),
            "polyline" => $color_code_vessel,
            "c_flag" => $c_flag,
            "vesselnum" => $vessel_number,
            "searatesTracking" => $_SESSION['searates']
        ]);
        $this->externalTemp();
    }

    public function tracking($user=""){

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
       
       
       $imageList = (Object) Model\User::getProfile($user);
       $profileImage = '/img/default-profile.png';
       foreach($imageList->user_image as $img){
           if( $img->image_src!="" && $img->image_type=='profile' ){
               $profileImage = base64_decode($img->image_src);
           }
       }

       $vessel_number = (isset($vessel_number[1])? $vessel_number[1] :'');
       $vloyds =(isset($this->Vessel->vesseLyod($vessel_number,$user)[0])? $this->Vessel->vesseLyod($vessel_number,$user)[0]->vesslloyds:'');
       
       $searates  = 'empty';
       if(!isset($_SESSION['vesselnum'])){
        $_SESSION['vesselnum'] = '';
       }
        //if(!isset($_SESSION['livesearates'])  ||  $_SESSION['vesselnum'] != $vessel_number ){
            $searates = file_get_contents('https://tracking.searates.com/route?type=CT&number='.$vessel_number.'&sealine=ANNU&api_key=OEHZ-7YIN-1P9R-T8X4-F632');
            $_SESSION['livesearates'] =  $searates;
            $_SESSION['vesselnum'] =$vessel_number;
        //}

        $this->View->addJS("js/tracking.js");
       $this->View->renderTemplate($role, $role . "/vessel/tracking", [
           "title" => "Vessel Track",
           "vesseldata" => $this->Vessel->getVesselByNumber($vessel_number,$user),
           "data" => (new Presenter\Profile($User->data()))->present(),
           "user" => (Object) Model\User::getProfile($user),
           "image_profile" => $profileImage,
           'selected_theme' => $selectedTheme,
           'role' => $role,
           'mapToken' => 'pk.eyJ1IjoidGl5bzE0IiwiYSI6ImNrbTA1YzdrZTFmdGIyd3J6OXFhbHcyYTEifQ.R2vfZbgOCPtFG6lgAMWj7A',
           'geocodeToken' => 'pk.fe49a0fae5b7f62ed12a17d8c2a77691',
           "notifications" => Model\User::getUserNotifications($user),
           "vesselyod" => $vloyds,
           "searatesTracking" => $_SESSION['livesearates']
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
        $count = 0;
       
        if(!empty($vessel)){
            foreach($vessel as $key=>$ves){
                $dateTrack = date_create($ves->date_track);
                $day = date_format($dateTrack,"l");
                $month = date_format($dateTrack,"F j,Y");
                $hour = date_format($dateTrack,'h:i:s A');

                $store[$ves->container_number]['vessel_name'][] =$ves->vessel;
                $store[$ves->container_number]['location_city'][] =$ves->location_city;
                $store[$ves->container_number]['date_track'][] =$month." - ".$hour;
                $store[$ves->container_number]['voyage'][]=$ves->voyage;

                
                // $link = '<a class="col-sm-3 dcontent '.$ves->container_number.'" href="/vessel/details?'.$ves->container_number.'">'.$ves->container_number.'</a>';
                // $subdata =array(); 
                // $subdata['container_number'] = $link;
                // $subdata['vessel_name'] = $ves->vessel;
                // $subdata['location_city'] = '<p class="loc-city">'.$ves->location_city.'</p>';
                // $subdata['date_track'] = $month." - ".$hour;
                // $subdata['status'] = $ves->moves;
                // $subdata['voyage'] = $ves->voyage;
                // //$subdata['action'] = ' <a class="col-sm-3 dcontent '.$ves->container_number.'" href="/vessel/details?'.$ves->container_number.'">Details</a>';
                // $subdata[''] = $key;

                // $data[] = $subdata; 
            }
           
            foreach($store as $key=>$st){
                $lastvessel = end($st['vessel_name']);
                $lastdate = end($st['vessel_name']);
                
                $subdata =array(); 
                $subdata['container_number'] = $key;
                $subdata['vessel_name'] = 'From: '.$st['vessel_name'][0].'<br> To: '.end($st['vessel_name']);
                $subdata['location_city'] = 'From: '.$st['location_city'][0].'<br> To: '.end($st['location_city']);
                $subdata['date_track'] = 'From: '.$st['date_track'][0].'<br>  To: '.end($st['date_track']);
                $subdata['voyage'] = 'From: '.$st['voyage'][0].'<br>  To: '.end($st['voyage']);
                
                $subdata['action'] = '<a class="col-sm-3 dcontent '.$key.'" href="/vessel/details?'.$key.'">Details</a>/
                <a class="col-sm-3 dcontent '.$key.'" href="/vessel/tracking?'.$key.'">Tracking</a>';
               
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
        echo '<link rel="stylesheet" href="http://turbo87.github.io/leaflet-sidebar/src/L.Control.Sidebar.css" crossorigin=""/>';
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
        echo '<script src="http://turbo87.github.io/leaflet-sidebar/src/L.Control.Sidebar.js"></script>';
        echo ' <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>';
        echo ' <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>';
    }

    public function scrape(){
        $data=array();
        if(isset($_POST)){
            
            $port = $this->Vessel->getSeaPort($_POST['port_name']);
            
            if(empty($port)){
                $dom = file_get_contents('https://us1.locationiq.com/v1/search.php?key=pk.fe49a0fae5b7f62ed12a17d8c2a77691&q='.$_POST['port_name'].'&format=json', false);
                $dom = json_decode($dom); 
                if(!empty($dom)){
                    foreach($dom as $prt){
                        $data['port_name'] = $prt->display_name;
                        $data['port_city'] = explode(',',$prt->display_name)[0];
                        $data['port_lat'] = $prt->lat;
                        $data['port_long'] = $prt->lon; 
                        $data['place_id'] = $prt->place_id;
                        $this->Vessel->addSeaPort($data);
                    }
                    
                }
            }
            
            echo json_encode($this->Vessel->getSeaPort($_POST['port_name']));
        }
    }
    
    // public function scrapeVessels(){
    //     $dom = file_get_contents('https://www.fleetmon.com/community/photos');
    //     echo "<pre>";
    //     print_r($dom);
    // }
    // public function country(){
    //     $data['name'] = $_POST['country_name']; 
    //     $data['code2'] = $_POST['country_code2']; 
    //     $data['code3'] = $_POST['country_code3']; 
    //     $data['area'] = $_POST['country_area']; 
    //     $data['region'] = $_POST['country_region']; 
    //     $data['flag'] = base64_encode(file_get_contents($_POST['country_flag'])); 
    //     $this->Vessel->addCountry($data);

    //     return json_encode($data);
    // }

    public function getFlag($country){
        return json_encode($this->Vessel->getFlag($country));
    }
}