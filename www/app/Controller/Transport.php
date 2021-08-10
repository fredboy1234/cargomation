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
        $role = $Role->getUserRole($user);

        //$shipment_id = $this->Shipment->getShipment($user, "shipment_num");
        if($role == 'user'){
            $shipment_id = $this->Shipment->getClientUserShipment($user, "shipment_num");
        }
        // $docsCollection =array();
        // foreach($this->Document->getDocumentByShipment($shipment_id) as $key=>$value){
        //     $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        // }

        $role = $Role->getUserRole($user);

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        $User->putUserLog([
            "user_id" => $user,
            "ip_address" => $User->getIPAddress(),
            "log_type" => 8,
            "log_action" => "Access transport",
            "start_date" => date("Y-m-d H:i:s"),
        ]);
        
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
        
        $this->View->renderTemplate("/transport/index", [
            "title" => "Transport App",
            "transport" => $this->Transport->getTransport($user),
            "data" => (new Presenter\Profile($User->data()))->present(),
            //"document" => $this->Document->getDocumentByShipment($shipment_id),
            //"document_per_type" => $docsCollection,
            //"child_user" => Model\User::getUsersInstance($user),
            "user_settings" =>$this->defaultSettings($user),
            "settings_user" => $User->getUserSettings($user),
            //"client_user_shipments" => $this->Shipment->getClientUserShipment($user),
            "image_profile" => $profileImage,
            'role' => $role,
            'selected_theme' => $selectedTheme,
            "user" => (Object) Model\User::getProfile($user),
            "droplist" =>$this->dropdownList(),
            "notifications" => Model\User::getUserNotifications($user),
            "menu" => Model\User::getUserMenu($role->role_id),
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

    public function advanceSearch($user ='',$post=""){
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
        
        return $this->Transport->getTransportSSR($post,$user);
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
        $role = $Role->getUserRole($user);

        //$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
        $api = $this->Transport->getTransport($user); 
        $searchStore = array();
       
        if(isset($_POST['post_trigger']) && $_POST['post_trigger'] != ""){
            $searchResult = $this->advanceSearch($user,$_POST);
            if(!empty($searchResult)){
                $api = $searchResult;
            }else{
                $api = array();
            }
           
        }
      
        foreach($api as $key=>$value){
            $fcl_unload = date_format(date_create($value->fcl_unload), "d/m/Y");
            $port_transport_booked = date_format(date_create($value->port_transport_booked), "d/m/Y");
            $slot_date = date_format(date_create($value->slot_date), "d/m/Y");
            $wharf_gate_out = date_format(date_create($value->wharf_gate_out), "d/m/Y");
            $estimated_full_delivery = date_format(date_create($value->estimated_full_delivery), "d/m/Y");
            $actual_full_deliver = date_format(date_create($value->actual_full_deliver), "d/m/Y");
            $empty_returned_by = date_format(date_create($value->empty_returned_by), "d/m/Y");
            $empty_readyfor_returned = date_format(date_create($value->empty_readyfor_returned), "d/m/Y");
            $trans_book_req = date_format(date_create($value->trans_book_req), "d/m/Y");
            $trans_actual_deliver = date_format(date_create($value->trans_actual_deliver), "d/m/Y");
            $trans_deliverreq_from = date_format(date_create($value->trans_deliverreq_from), "d/m/Y");
            $trans_deliverreq_by = date_format(date_create($value->trans_deliverreq_by), "d/m/Y");
            $trans_estimated_delivery = date_format(date_create($value->fcl_unload), "d/m/Y");
            $trans_delivery_labour = date_format(date_create($value->trans_delivery_labour), "d/m/Y");
            $trans_wait_time = date_format(date_create($value->trans_wait_time), "d/m/Y");
            
            
            $subdata =array(); 
            
            $subdata['transport_id'] = $value->trans_id;
            $subdata['shipment_num'] = $value->shipment_num;
            $subdata['container_number'] = $value->containernumber;
            $subdata['voyage_flight_num'] = $value->voyage_flight_num;
            $subdata['vesslloyds'] = $value->vesslloyds;
            $subdata['transport_mode'] = $value->transport_mode;
            $subdata['container_type'] = $value->containertype;
            $subdata['container_delivery_mode'] = $value->containerdeliverymode;
            $subdata['container_description'] = $value->containerdescription;
            $subdata['fcl_unload'] = ($fcl_unload=="01/01/1900"?"No Date Available":$fcl_unload);
            $subdata['port_transport_booked'] = ($port_transport_booked=="01/01/1900"?"No Date Available":$port_transport_booked);
            $subdata['slot_date'] = ($slot_date=="01/01/1900"?"No Date Available":$slot_date);
            $subdata['container_number'] = $value->containernumber;
            $subdata['vessel_name'] = $value->vessel_name;
            $subdata['wharf_gate_out'] = ($wharf_gate_out=="01/01/1900"?"No Date Available":$wharf_gate_out);
            $subdata['estimated_full_delivery'] = ($estimated_full_delivery=="01/01/1900"?"No Date Available":$estimated_full_delivery);
            $subdata['actual_full_deliver'] = ($actual_full_deliver=="01/01/1900"?"No Date Available":$actual_full_deliver);
            $subdata['empty_returned_by'] = ($empty_returned_by=="01/01/1900"?"No Date Available":$empty_returned_by);
            $subdata['customs_ref'] = $value->customs_Ref;
            $subdata['empty_readyfor_returned'] = ($empty_readyfor_returned=="01/01/1900"?"No Date Available":$empty_readyfor_returned);
            $subdata['port_transport_ref'] = $value->port_transport_ref;
            $subdata['slot_book_ref'] = $value->slot_book_ref;
            $subdata['do_release'] = $value->do_release;
            
            $subdata['trans_book_req'] = ($trans_book_req=="01/01/1900"?"No Date Available":$trans_book_req);
            $subdata['trans_actual_deliver'] = ($trans_actual_deliver=="01/01/19000"?"No Date Available":$trans_actual_deliver);
            $subdata['trans_deliverreq_from'] = ($trans_deliverreq_from=="01/01/1900"?"No Date Available":$trans_deliverreq_from);
            $subdata['trans_deliverreq_by'] = ($trans_deliverreq_by=="01/01/1900"?"No Date Available":$trans_deliverreq_by);
            $subdata['trans_estimated_delivery'] = ($trans_estimated_delivery=="01/01/1900"?"No Date Available":$trans_estimated_delivery);
            $subdata['trans_delivery_labour'] = ($trans_delivery_labour=="01/01/1900"?"No Date Available":$trans_delivery_labour);
            $subdata['trans_wait_time'] = ($trans_wait_time=="01/01/1900"?"No Date Available":$trans_wait_time);
            
            $data[] = $subdata;
            
            
        }
       
        $json_data=array(
            "data"  =>  $data,
        );
        
        echo json_encode($json_data);
    }

    public function dropdownList(){
        $list = array(
            array("value"=>"fcl_unload", "text"=>"FCL Unload"),
            array("value"=>"port_transport_booked", "text"=>"Port Transport Booked"),
            array("value"=>"fslot_date", "text"=>"F Slot Date"),
            array("value"=>"wharf_gate_out", "text"=>"Wharf Gate Out"),
            array("value"=>"estimated_full_delivery", "text"=>"Estimated Full Delivery"),
            array("value"=>"empty_returned_by", "text"=>"Empty Returned By"),
            array("value"=>"empty_readyfor_returned", "text"=>"Empty Ready For Returned"),
            array("value"=>"trans_book_req", "text"=>"Trans Book Req"),
            array("value"=>"trans_actual_deliver", "text"=>"Trans Actual Delivery"),
            array("value"=>"trans_deliverreq_from", "text"=>"Trans Delver Req From"),
            array("value"=>"trans_deliverreq_by", "text"=>"Trans Deliver Req By"),
            array("value"=>"trans_estimated_delivery", "text"=>"Trans Estimated Delivery"),
            array("value"=>"trans_delivery_labour", "text"=>"Trans Delivery Labour"),
            array("value"=>"trans_wait_time", "text"=>"Trans Wait Time")
        );
        return $list;
    }
}