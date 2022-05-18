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
    // public function index() {
        
    //     // Check that the user is authenticated.
    //     Utility\Auth::checkAuthenticated();
    //     Utility\Cookie::delete('redirectLink');
    //     // Get an instance of the user model using the ID stored in the session. 
    //     $userID = Utility\Session::get(Utility\Config::get("SESSION_USER"));
        
    //     if (!$User = Model\User::getInstance($userID)) {
    //         Utility\Redirect::to(APP_URL);
    //     }        
       
    //     if (!$Role = Model\Role::getInstance($userID)) {
    //         Utility\Redirect::to(APP_URL);
    //     }
       
    //     $Shipment = Model\Shipment::getInstance();

    //     $role = $Role->getUserRole($userID);
        
    //     // var_dump(Model\User::getUserNotifications($userID));
    //     //  die();

    //     if(empty($role)) {
    //         Utility\Redirect::to(APP_URL . $role);
    //     }
        
    //     // assign value by user role
    //     switch ($role->role_id) {
    //         case 1: // SUPER ADMIN
    //             $data['is_customer'] = false;
    //             $document_stats = Model\Document::getDocumentStats($userID);
    //             break;
    //         case 2: // CLIENT ADMIN
    //             $data['is_customer'] = false;
    //             $document_stats = Model\Document::getDocumentStats($userID);
    //             break;
    //         case 3: // STAFF
    //             $data['is_customer'] = false;
    //             $document_stats = Model\Document::getDocumentStats($userID);
    //             break;
    //         case 4: // CUSTOMER
    //             $org_code = Model\User::getUserInfoByID($userID)[0]->organization_code;
    //             $data['is_customer'] = true;
    //             if($org_code == NULL) {
    //                 $org_code = $User->getUserContactInfo($userID)[0]->organization_code;
    //             }
    //             $data['org_code'] = $org_code;
    //             $document_stats = Model\Document::getDocumentStats($userID, $org_code);
    //             break;
            
    //         default:
    //             die('Error in getting the user role');
    //             break;
    //     }
        
        
    //     $User->putUserLog([
    //         "user_id" => $userID,
    //         "ip_address" => $User->getIPAddress(),
    //         "log_type" => 2,
    //         "log_action" => "Access dashboard",
    //         "start_date" => date("Y-m-d H:i:s"),
    //     ]);


    //     $selectedTheme = $User->getUserSettings($userID);
       
    
    //     if(isset($selectedTheme[0]) && !empty($selectedTheme)){
    //         $selectedTheme = $selectedTheme[0]->theme;
    //     }else{
    //         $selectedTheme = 'default';
    //     }
        
    //     $this->View->addCSS("css/theme/".$selectedTheme.".css");
    //     $this->View->addCSS("css/".$selectedTheme.".css");
    //     $this->View->addJS("js/dashboard.js");
        
    //     // Render view template
    //     // Usage renderTemplate(string|$template, string|$filepath, array|$data)

    //     $imageList = (Object) Model\User::getProfile($userID);
    //     $profileImage = '/img/default-profile.png';
    //     foreach($imageList->user_image as $img){
    //         if( $img->image_src!="" && $img->image_type=='profile' ){
    //             $profileImage = base64_decode($img->image_src);
    //         }
    //     }
       
    //     //$cmode = $Shipment->getShipmentDynamic($userID,'container_mode, transport_mode', 'containermode', $data);
    //     //$cmodeArray = array();
    //     $seacount = 0;
    //     $aircount =0;
    //     // foreach($cmode as $value){
    //     //     if($value->transport_mode === 'Sea'){
    //     //         $cmodeArray['sea'][$value->container_mode][] = $value;
    //     //         $seacount++;
    //     //     }else{
    //     //         $cmodeArray['air'][$value->container_mode][] = $value;
    //     //         $aircount++;
    //     //     }
           
    //     // }
    //     //echo"<pre>";
    //     $shipmentcount = $Shipment->getShipmentCount($userID);
    //     $shiparr = array();
    //     $ctotal = 0;
    //     foreach($shipmentcount as $ship){
    //         $ctotal += $ship->count;
    //         $shiparr[$ship->transport_mode] = $ship->count;    
    //     }
        
    //     $this->View->renderTemplate("/dashboard", [
    //         "title" => "Dashboard",
    //         "data" => (new Presenter\Profile($User->data()))->present(),
    //         "notifications" => Model\User::getUserNotifications($userID),
    //         "user" => (Object) Model\User::getProfile($userID),
    //         "users" => Model\User::getUsersInstance($userID, $role->role_id),
    //         "user_log" => Model\User::getUsersLogInstance($userID, $role->role_id),
    //         "menu" => Model\User::getUserMenu($role->role_id),
    //         "image_profile" => $profileImage,
    //         "selected_theme" => $selectedTheme,
    //         "role" => $role,
    //         "total_shipment" =>$ctotal,
    //         "not_arrived" => $Shipment->getShipmentnotArrived($userID)[0]->count,
    //         "air_shipment" => $shiparr['Air'],
    //         "sea_shipment" => $shiparr['Sea'],
    //         "shipment_with_port" => array(),
    //         "port_loading_count" =>$this->getMapCount($userID),
    //         "document_stats" => $document_stats,
    //         "uid"=>$userID,
    //     ]);
    //     $this->externalTemp();
    // }


    public function index() {
        
        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        Utility\Cookie::delete('redirectLink');

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
        
       
        $User->putUserLog([
            "user_id" => $userID,
            "ip_address" => $User->getIPAddress(),
            "log_type" => 2,
            "log_action" => "Access dashboard",
            "start_date" => date("Y-m-d H:i:s"),
        ]);


        $selectedTheme = $User->getUserSettings($userID);
        $dashboardTheme = '';
        
        if(isset($selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
            $dashboardTheme = json_decode($User->getUserSettings($userID)[0]->dashboard);
        }else{
            $selectedTheme = 'default';
        }
       

        if(isset($dashboardTheme->dash) && $dashboardTheme->dash == "dash_v1"){
            $this->View->addCSS("css/dashboardv2.css");
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
            "users" => Model\User::getUsersInstance($userID, $role->role_id),
            "user_log" => Model\User::getUsersLogInstance($userID, $role->role_id),
            "menu" => Model\User::getUserMenu($role->role_id),
            "image_profile" => $profileImage,
            "selected_theme" => $selectedTheme,
            "role" => $role,
            "uid"=>$userID,
            'dashtheme'=>$dashboardTheme,
        ]);
        $this->externalTemp();
    }



    public function processShipmentCount(){
        $userID = $_POST['userid'];
        $rolename = $_POST['rolename'];
        $data = $this->docstats($userID);
        $Shipment = Model\Shipment::getInstance();
        $shipmentcount = $Shipment->getShipmentCount($userID,$rolename,$data);
        $shiparr = array();
        $ctotal = 0;
        
        foreach($shipmentcount as $ship){
            $ctotal += $ship->count;
            $shiparr[$ship->transport_mode] = $ship->count;    
        }
        $notarrived = isset($Shipment->getShipmentnotArrived($userID)[0]->count) ? $Shipment->getShipmentnotArrived($userID)[0]->count : 0;
        echo json_encode([
            "total_shipment"=>$ctotal,
            "not_arrived" => $notarrived ,
            "air" => isset($shiparr['Air']) ? $shiparr['Air'] : 0,
            "sea" => isset($shiparr['Sea']) ? $shiparr['Sea']  : 0,
        ]);
       
    }

    public function processMapCount(){
        $userID = $_POST['userid'];
        $mapcount = json_decode($this->getMapCount($userID));
       
        $loadingCol=array();
        $loading = array(); 
        $sea=array();
        $air=array();
        $others = array();
        
        foreach($mapcount as $mc){
            
            if($mc->mode === "Sea"){
                if(!isset($loading['sea'][$mc->port_loading])){
                    array_push($loadingCol,$mc);
                }
                $loading['sea'][$mc->port_loading]=$mc;
            }else if($mc->mode === "Air"){
                if(!isset($loading['air'][$mc->port_loading])){
                    array_push($loadingCol,$mc);
                }
                $loading['air'][$mc->port_loading]=$mc;
            }else{
                $loading['others'][$mc->port_loading]=$mc;
            }
        }

        echo json_encode([
            "port_loading_count" =>  json_encode($loadingCol)
        ]);
       
    }

    public function processDocStats(){
        $userID = $_POST['userid'];
        
        $document_stats = $this->docstats($userID)['doctstats'];

        $uploaded= isset($document_stats['total_files'][0]->count) ? $document_stats['total_files'][0]->count : 0;
        $approval = isset($document_stats['pending_files'][0]->count) ? $document_stats['pending_files'][0]->count : 0;
        $requested= isset($document_stats['new_request'][0]->count) ?$document_stats['new_request'][0]->count : 0;
        $updated= isset($document_stats['update_request'][0]->count) ? $document_stats['update_request'][0]->count : 0;
        
        echo json_encode([
            "uploaded" => $uploaded,
            "approval" => $approval,
            "requested" => $requested,
            "updated" => $updated,
        ]);
    }

    public function docstats($userID){
        if (!$Role = Model\Role::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }
        $role = $Role->getUserRole($userID);
        switch ($role->role_id) {
            case 1: // SUPER ADMIN
                $data['is_customer'] = false;
                $document_stats = Model\Document::getDocumentStats($userID);
                break;
            case 2: // CLIENT ADMIN
                $data['is_customer'] = false;
                $document_stats = Model\Document::getDocumentStats($userID);
                break;
            case 3: // STAFF
                $data['is_customer'] = false;
                $document_stats = Model\Document::getDocumentStats($userID);
                break;
            case 4: // CUSTOMER
                $org_code = Model\User::getUserInfoByID($userID)[0]->organization_code;
                $data['is_customer'] = true;
                if($org_code == NULL) {
                    $org_code = $User->getUserContactInfo($userID)[0]->organization_code;
                }
                $data['org_code'] = $org_code;
                $document_stats = Model\Document::getDocumentStats($userID, $org_code);
                break;
            
            default:
                die('Error in getting the user role');
                break;
        }
        $data['doctstats'] = $document_stats;
        return $data;
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

    public function getMapCount($userID){
       $url = 'https://cargomation.com:5200/redis/getmapshipment';
       $arr = [
            "user_id" =>$userID,
        ];
        $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
        $headers = ["Authorization: Basic YWRtaW46dVx9TVs2enpBVUB3OFlMeA==",
                    "Content-Type: application/json"];
        $result = $this->post($url, $payload, $headers);
        
        $json_data = json_decode($result);
        //print_r($json_data);
        if($json_data->status != '200') {
            echo json_encode($json_data);
            exit;
        }else{
            return $json_data->data;
        }
    }

    /**
     * Post: uses CURL to call a request to the endpoint and 
     * return mixed data response.
     * @access private
     * @param string $url url of the endpoint
     * @param mixed $payload  obj,array,string,int
     * @example $data = json_encode($array, JSON_UNESCAPED_SLASHES);
     * @param string $headers  curl header options
     * @example $headers = ["Content-Type: application/json"];
     * @return mixed response
     * @since 1.0
     */
    private function post($url, $payload, $headers) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $payload,
        ));
        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($curl);
        $errno = curl_errno($curl);
        if ($errno) {
            return false;
        }
        curl_close($curl);
        return $response;
    }

}