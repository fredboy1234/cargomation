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
class Transport extends Core\Controller {

    public function __construct($requestMethod = '', $key = '', $value = '', $param = []) {
        // Create a new instance of the model shipment class.
        $this->Transport = Model\Transport::getInstance();
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

        //$shipment_id = $this->Shipment->getShipment($user, "shipment_num");
        if($role == 'user'){
            $shipment_id = $this->Shipment->getClientUserShipment($user, "shipment_num");
        }
        // $docsCollection =array();
        // foreach($this->Document->getDocumentByShipment($shipment_id) as $key=>$value){
        //     $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        // }

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
        $this->View->addCSS("css/transport.css");
        $this->View->addJS("js/transport.js");

        $imageList = (Object) Model\User::getProfile($user);
        $profileImage = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }
        // echo "<pre>";
        // print_r($this->defaultSettings($user));exit();
        // echo "</pre>";
        $this->View->renderTemplate($role, $role . "/transport/index", [
            "title" => "Transport App",
            "transport" => $this->Transport->getTransport($user),
            //"data" => (new Presenter\Profile($User->data()))->present(),
            //"document" => $this->Document->getDocumentByShipment($shipment_id),
            //"document_per_type" => $docsCollection,
            //"child_user" => Model\User::getUsersInstance($user),
            "user_settings" =>$this->defaultSettings($user),
            "settings_user" => $User->getUserSettings($user),
            //"client_user_shipments" => $this->Shipment->getClientUserShipment($user),
            //"image_profile" => $profileImage,
            //'role' => $role,
            //'selected_theme' => $selectedTheme
        ]);
    }

    public function defaultSettings($user=""){

        Utility\Auth::checkAuthenticated();

        if (!$user) {
            $userSession = Utility\Config::get("SESSION_USER");
            if (Utility\Session::exists($userSession)) {
                $user = Utility\Session::get($userSession);
            }
        }
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }
        $userData = $User->getUserSettings($user);
        $userData = !empty($userData)&&isset($userData[0]->transport)?json_decode($userData[0]->transport):array();
        
        $defaultSettings = json_decode(file_get_contents(PUBLIC_ROOT.'/settings/transport-settings.json'));
        
        $defaultCollection = array();
        if(isset($userData) && !empty($userData)){
            foreach($userData as $key => $value){
                $defaultCollection[]=$value->index_value;
            }
        }
        if(empty($defaultCollection)){
            $userData = array();
        }
        foreach($defaultSettings->table  as $key=> $value){
            if(!empty($defaultCollection)){
                if(!in_array($value->index_value,$defaultCollection)){
                    $value->index_check = 'false';
                    $userData[] = $value;
                } 
            }else{
                $userData[] = $value;
            }
        }
        
       return json_encode($userData);
    }

    public function transportSSR($user=""){
        $data = array();
        $docsCollection = array();
        $json_data = array();
        $html = array();
        $tableData = array();
        $status_search = array('Approved','Pending','Missing');
         Utility\Auth::checkAuthenticated();

         if (!$user) {
             $userSession = Utility\Config::get("SESSION_USER");
             if (Utility\Session::exists($userSession)) {
                 $user = Utility\Session::get($userSession);
             }
         }
        
        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }
        $role = $Role->getUserRole($user)->role_name;

        //$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
        $api = $this->Transport->getTransport($user); 
        $searchStore = array();
       
        if(isset($_POST['post_trigger']) && $_POST['post_trigger'] != ""){
            $status_search = explode(",",$_POST['status']);
            $searchResult = $this->advanceSearch($user,$_POST);
            if(!empty($searchResult)){
                foreach($searchResult as $id){
                    foreach($api as $val){
                        if($id->id == $val->id){
                            $searchStore[] = $val;
                        }
                    }
                }
                $api = $searchStore;
            }else{
                $api = array();
            }
        }
        
        // $shipment_id = $this->Shipment->getShipment($user, "shipment_num");
        // if($role == 'user'){
        //     $shipment_id = $this->Shipment->getClientUserShipment($user, "shipment_num");
        //     $api = $this->Shipment->getClientUserShipment($user);
        // }
        // foreach($this->Document->getDocumentByShipment($shipment_id) as $key=>$value){
        //     $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        // }
        
        // $stats = $docsCollection;
        $doc_type = array('HBL','CIV','PKL','PKD','all');
        //$settings = array("Shiment ID","Console ID","ETA","HBL","CIV","PKL","PKD","ALL","Comment");
        foreach($api as $key=>$value){
            $eta_date = date_format(date_create($value->eta), "d/m/Y H:i:s");
            $etd_date = date_format(date_create($value->etd), "d/m/Y H:i:s");
            $all = "";
            
              $tableData = [];

                $subdata =array(); 
                $subdata['transport_id'] = $value->trans_id;
                $subdata['container_number'] = $value->containernumber;
                $subdata['vessel_name'] = $value->vessel_name;
                $subdata['eta'] = $eta_date;
                $subdata['etd'] = $etd_date ;
                $subdata['voyage_flight_number'] = $value->voyage_flight_num;
            
                $data[] = $subdata;
            
            
        }
       //exit();
        $json_data=array(
            "data"  =>  $data,
        );
        
        echo json_encode($json_data);
    }
}