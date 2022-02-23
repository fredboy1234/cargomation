<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

/**
 * Shipment Controller:
 *
 * @author John Alex
 * @since 1.0.8
 */

class Shipment extends Core\Controller {

    private $requestMethod;
    private $param;
    private $value;
    private $key;
    protected $Shipment = null;
    protected $Document = null;
    protected $View = null;

    public function __construct($requestMethod = '', $key = '', $value = '', $param = []) {
        // Create a new instance of the model shipment class.
        $this->Shipment = Model\Shipment::getInstance();
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
     * Shipment Index: Renders the shipment view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function index($user = "") {

        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        Utility\Cookie::delete('redirectLink');

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

        $shipment_id = $this->Shipment->getShipment($user, "shipment_num");
        $doc_by_ship = $this->Document->getDocumentByShipment($shipment_id);

        $docsCollection =array();
        foreach($doc_by_ship as $key=>$value){
            $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        }

        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user);

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        switch ($role->role_id) {
            case 1:
                $user_key = $user;
                break;
            case 2:
                $user_key = $user;
                break;
            case 3:
                $sub_account = $User->getSubAccountInfo($user);
                $user_key = $sub_account[0]->account_id;
                break;
            case 4:
                $sub_account = $User->getSubAccountInfo($user);
                $user_key = $sub_account[0]->email;
                break;
            
            default:
                $user_key = $user;
                break;
        }

        $User->putUserLog([
            "user_id" => $user,
            "ip_address" => $User->getIPAddress(),
            "log_type" => 3,
            "log_action" => "Access doctracker",
            "start_date" => date("Y-m-d H:i:s"),
        ]);

        // Set any dependencies, data and render the view.
        $selectedTheme = $User->getUserSettings($user);
        
        if(isset( $selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = 'default';
        }

        $this->View->addCSS("css/shipment.css");
        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        $this->View->addJS("js/shipment.js");

        $imageList = (Object) Model\User::getProfile($user);
        $profileImage = '/img/default-profile.png';
        $emailList = $this->Shipment->getShipmentThatHasUser($user);
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }
        $searchFilter = json_decode(file_get_contents(PUBLIC_ROOT.'/settings/search-filter.json'));
        // echo "<pre>";
        // print_r($searchFilter);
        // exit();
        $this->View->renderTemplate("/doctracker/index", [
            "title" => "Shipment View",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => $imageList,
            "notifications" => Model\User::getUserNotifications($user),
            "menu" => Model\User::getUserMenu($role->role_id),
            // "shipment" => $this->Shipment->getShipment($user),
            // "document" => $doc_by_ship,
            // "document_per_type" => $docsCollection,
            // "child_user" => Model\User::getUsersInstance($user, $role->role_id),
            "user_settings" =>$this->defaultSettings($user, $role->role_id), // $user_key??
            "settings_user" => $selectedTheme,
            // "client_user_shipments" => $this->Shipment->getClientUserShipment($user),
            "image_profile" => $profileImage,
            'role' => $role,
            'user_id' => $user,
            'selected_theme' => $selectedTheme,
            'searchfilter' =>$searchFilter,
            //'shipment_from_contact'=> $emailList
        ]);
    }

    public function processShipment() {
        // Processing request.. 
        switch ($this->requestMethod) {
            case 'POST':
                switch ($this->key) {
                    case 'settings': 
                        $response = $this->addShipmentSettings();
                        break;
                    default:
                        $response = "test";
                        break;
                }
                // $response = $this->createShipmentFromRequest();
                // break;
            case 'GET':
                switch ($this->key) {
                    case 'uid': 
                        $response = $this->getShipmentByUserID($this->value, $this->param);
                        break;
                    case 'sid': 
                        $response = $this->getShipmentByShipID($this->value, $this->param);
                        break;
                    case 'did':
                        $response = $this->getShipmentByDocID($this->value, $this->param);
                        break;
                    case 'org':
                        $response = $this->getShipmentByOrgCode($this->value);
                        break;
                    case 'all':
                        $response = $this->getAllShipment();
                        break;
                    default:
                        $response = $this->unprocessableEntityResponse();
                        break;
                }
                break;
            case 'PUT':
                // $response = $this->updateShipmentFromRequest($this->userId);
                // break;
            case 'DELETE':
                // $response = $this->deleteShipment($this->userId);
                // break;

            default:
                $response = $this->notFoundResponse();
                break;
        }
        // echo json_encode(array("results" => $response));
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function unauthorizedAccess() {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([
            'error' => 'Stop! Higher clearance is needed to access this data.'
        ]);
        return $response;
    }

    private function unprocessableEntityResponse() {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse() {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }

    // API Get shipment by document_id
    private function getShipmentByDocID($document_id, $param) {
        $args = (isset($param[6]) && !empty($param[6])) ? $param[6] : "*";
        $result = $this->Shipment->getShipmentByDocID($document_id, $args);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;

    }

    // API Get shipment by shipment_id
    private function getShipmentByShipID($shipment_id, $param) {
        $args = (isset($param[6]) && !empty($param[6])) ? $param[6] : "*";
        $result = $this->Shipment->getShipmentByShipID($shipment_id, $args);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    // API Get shipment by user_id
    private function getShipmentByUserID($user_id, $param) {
        $args = (isset($param[6]) && !empty($param[6])) ? $param[6] : "*";
        $result = $this->Shipment->getShipmentByUserID($user_id, $args);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    // API Get shipment by org_code
    private function getShipmentByOrgCode($org_code) {
        $result = $this->Shipment->getShipmentByOrgCode($org_code);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
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

        //$api_url = "https://cargomation.com/eAdaptor/jsoneAdaptor.php?shipment_id=" . $shipment_id . "&request=document";

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

        if (!$Role = Model\Role::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user_id);

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        $email = $User->data()->email;

        // get client admin email
        if(!empty($User->getSubAccountInfo($user_id))) {
            $sub_account = $User->getSubAccountInfo($user_id);
            // "user email" change to "client email"
            $email = $sub_account[0]->client_email;
        }

        $this->View->addJS("js/document.js");
        $this->View->addCSS("css/document.css");

        $this->View->renderWithoutHeaderAndFooter("/document/index", [
            "title" => "Shipment API",
            "id" => $user_id,
            "email" => $email,
            "role" => $role,
            "shipment" => ["shipment_id" => $shipment_id, "type" => $type], 
            "shipment_info" => $this->Shipment->getShipmentByShipID($shipment_id), 
            "document" => $this->Document->getDocumentByShipment($shipment_id, $type),
            "user_settings" => $User->getUserSettings($user_id)
        ]);
    }

    public function advanceSearch($user ='',$post=""){
        // Check that the user is authenticated.
        // Utility\Auth::checkAuthenticated();

        // // If no user ID has been passed, and a user session exists, display
        // // the authenticated users profile.
        // if (!$user) {
        //     $userSession = Utility\Config::get("SESSION_USER");
        //     if (Utility\Session::exists($userSession)) {
        //         $user = Utility\Session::get($userSession);
        //     }
        // }
        
        return $this->Shipment->getDocumentBySearch($post,$user);
    }

    public function shipmentAssign($user=""){
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
        echo json_encode($this->Shipment->shipmentAssign($_POST,$user));
    }

    public function shipmentSSR($user=""){
        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

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

        # Check user roles
        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }
        $role = $Role->getUserRole($user);

        # Initialize multiple array variables with Empty values simultaneously
        $data = $docsCollection = $json_data = $html = $tableData = $searchStore = array();
        # Initialize shipment API, status, shipment_id
        $status_search = array('Approved','Pending','Missing','Requested','Empty');
        $doc_type = array_column($this->Document->getDocumentType(), 'type');
        $shipment_link = $this->Shipment->getShipmentLink($user);

        # check User if role is user
        
        // if($role->role_id == 3){
        //     $shipment_id = $this->Shipment->getClientUserShipment($user, "shipment_num");
        //     $api = $this->Shipment->getClientUserShipment($user);
        // } else {
        //     $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
        //     $api = json_decode(file_get_contents($protocol . $_SERVER['HTTP_HOST'] . '/api/get/shipment/uid/'.$user)); 
        //     $shipment_id = $this->Shipment->getShipment($user, "shipment_num");
        // }

        // Get the shipment by role id 
        switch ($role->role_id) {
            case 1: // SUPER ADMIN
                $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
                $api = json_decode(file_get_contents($protocol . $_SERVER['HTTP_HOST'] . '/api/get/shipment/uid/'.$user)); 
                $shipment_id = $this->Shipment->getShipment($user, "shipment_num");
                break;
            case 2: // CLIENT ADMIN
                $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
                $api = json_decode(file_get_contents($protocol . $_SERVER['HTTP_HOST'] . '/api/get/shipment/uid/'.$user)); 
                $shipment_id = $this->Shipment->getShipment($user, "shipment_num");
                $user_key = $user;
                break;
            case 3: // STAFF
                $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
                $user_key = $User->getSubAccountInfo($user)[0]->account_id;
                $api = json_decode(file_get_contents($protocol . $_SERVER['HTTP_HOST'] . '/api/get/shipment/uid/'.$user_key)); 
                $shipment_id = $this->Shipment->getShipment($user, "shipment_num");
                // $shipment_id = $this->Shipment->getClientUserShipment($user, "shipment_num");
                // $api = $this->Shipment->getClientUserShipment($user);
                break;
            case 4: // CUSTOMER
                // $shipment_id = $this->Shipment->getClientUserShipment($user, "shipment_num");
                // $api = $this->Shipment->getClientUserShipment($user);
                $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
                $org_code = Model\User::getUserInfoByID($user)[0]->organization_code;
                if($org_code == NULL) {
                    $org_code = $User->getUserContactInfo($user)[0]->organization_code;
                }
                $shipment_id = $this->Shipment->getShipmentByOrgCode($org_code, "shipment_num");
                #$api = $this->Shipment->getShipmentByOrgCode($org_code);
                $api = json_decode(file_get_contents($protocol . $_SERVER['HTTP_HOST'] . '/api/get/shipment/org/'.$org_code));
                $user_key = $User->getSubAccountInfo($user)[0]->account_id;
                break;
            
            default:
                die('Error in getting the user role');
                break;
        }
        
        //$doc_requested = $this->Document->getRequestedDocument($user, "shipment_num, document_type, count(*) AS count", " shipment_num, document_type");        
        //$doc_requested = array_combine(array_map(function ($o) { return $o->shipment_num; }, $doc_requested), $doc_requested);

        # Advance search
        if(isset($_POST['post_trigger']) && $_POST['post_trigger'] != ""){
            // $status_search = explode(",",$_POST['status']);
            $searchResult = $this->advanceSearch($user_key,$_POST);
           
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
            //$shipment_id = $_POST['shipment_id'];
        }
        
        foreach($this->Document->getDocumentByShipment($shipment_id) as $key=>$value){
            $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        }

        // Get all requested document type
        $requested_doc = $this->Document->getRequestedDocument($user_key, "shipment_num, document_type");
        // Reconstruct arry object to multidimensional array
        foreach ($requested_doc as $key => $value) {
            $requested_doc[$key] = [
                'shipment_num' => $value->shipment_num,
                'document_type' => $value->document_type,
            ];
        }

        $stats = $docsCollection;

        foreach($api as $key=>$value){
            $eta_date = date_format(date_create($value->eta), "d/m/Y");
            $etd_date = date_format(date_create($value->etd), "d/m/Y");
            
            $etd_date_sort = date_format(date_create($value->etd), "d/m/Y");
            $eta_date_sort = date_format(date_create($value->eta), "d/m/Y");

            $status_arr['all']['pending2'] = 0;
            $status_arr['all']['approved2'] = 0;
            $status_arr['all']['count'] = 0;
            $status_arr["all"]["color"] = "badge-default";
            $status_arr["all"]["text"] = "Empty";
            $marcoLink = '';
            
            # Initialize macro_link from shipment_link
            foreach ($shipment_link as $key => $link) {
                if($value->shipment_num === $link->shipment_num) {
                    $marcoLink = $link->macro_link;
                }
            }
            
            # Check in document type list is empty
            if(!empty($doc_type)) {
                foreach ($doc_type as $type) {
                    $status_arr[$type]["color"] = "badge-default";
                    $status_arr[$type]["text"] = "Empty";
                    $status_arr[$type]['approved2'] = 0;
                    $status_arr[$type]['pending2'] = 0;
                }
                if(isset($stats[$value->shipment_num])) {
                    foreach ($stats[$value->shipment_num] as $key2 => $value2) {
                        if(isset($value2['pending'])) {
                            $status_arr[$value2['pending'][0]->type]['pending2'] = count($value2['pending']);
                            $status_arr['all']['pending2'] += count($value2['pending']);
                        }  
                        if(isset($value2['approved'])) {
                            $status_arr[$value2['approved'][0]->type]['approved2'] = count($value2['approved']);
                            $status_arr['all']['approved2'] += count($value2['approved']);
                        }
                        //for badge and text
                        if(isset($value2['pending'])) {
                            $status_arr[$value2['pending'][0]->type]["color"] = "badge-warning";
                            $status_arr[$value2['pending'][0]->type]["text"] = "Pending";
                            $status_arr["all"]["color"] = "badge-warning";
                            $status_arr["all"]["text"] = "View All";
                        }elseif(isset($value2['approved'])){
                            $status_arr[$value2['approved'][0]->type]["color"] = "badge-success";
                            $status_arr[$value2['approved'][0]->type]["text"] = "Approved";
                            $status_arr["all"]["color"] = "badge-success";
                            $status_arr["all"]["text"] = "View All";
                        }
                    }
                    $status_arr["all"]["color"] = "badge-primary";
                    $status_arr["all"]["text"] = "View All";
                }

                // For requested document stats
                foreach ($requested_doc as $key => $request) {
                    if($value->shipment_num == $request['shipment_num']) {
                        foreach ($doc_type as $type) {
                            if($type == $request['document_type']) {
                                $status_arr[$type]["color"] = "badge-info";
                                $status_arr[$type]["text"] = "Requested";
                            }
                        }
                    }
                }
            }


            //var_dump($status_arr); die();

            foreach($status_arr as $key => $val){
                $attr = ($key == "all" ? "" :'data-type="'.$key.'"');
                #if(in_array($key, $doc_type)){
                    $attr = ($key=="all"?"":'data-type="'.$key.'"');
                    $html[$key]['hover'] ='<div class="doc-stats" style="display: none;"><span class="doc" '.$attr.' data-id="'.$value->shipment_num.'">'.(isset($val["approved2"]) ? $val["approved2"] : 0).'<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>'.(isset($val["pending2"]) ? $val["pending2"] : 0).'<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i> 0<i class="fa fa-eye text-warning" aria-hidden="true"></i></span></div>';
                    $html[$key]['badge'] ='<span class="doc badge '.($val['color']).'" '.$attr.' data-id="'.$value->shipment_num.'">'.($val['text']).'</span>';
                    
                    if(isset($val['pending2']) && $val['pending2'] > 0){
                        $html[$key]['count'] ='<span class="badge badge-danger navbar-badge ship-badge">'.$val['pending2'].'</span>';
                    }elseif(isset($val['approved2']) && $val['approved2'] > 0){
                        $html[$key]['count'] ='<span class="badge badge-danger navbar-badge ship-badge">'.$val['approved2'].'</span>';
                    }else{
                        $html[$key]['count'] = "";
                    }
                #} else {
                // $html['all']['badge'] ='<span class="doc badge '.($val['color']).'" '.$attr.' data-id="'.$value->shipment_num.'">'.($val['text']).'</span>';
                // $html['all']['hover'] = '<div class="doc-stats" style="display: none;"><span class="doc" data-type="HBL" data-id="'.$value->shipment_num.'">0<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>0<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i> 0<i class="fa fa-eye text-warning" aria-hidden="true"></i></span></div>';
                // $html[$key]['count'] = "";
            }
            foreach($doc_type as $doc){
                if(isset($html[$doc]['hover'])){
                    $tableData[$doc]['hover'] = $html[$doc]['hover'];
                    $tableData[$doc]['badge'] = $html[$doc]['badge'];
                    if(isset($html[$doc]['count'])){
                        $tableData[$doc]['count'] =   $html[$doc]['count'];
                    }else{
                        $tableData[$doc]['count'] = "";
                    }
                }else{
                    $tableData[$doc]['hover'] = $html['all']['hover'];
                    $tableData[$doc]['badge'] = $html['all']['badge'];
                    $tableData[$doc]['count'] = "";
                }
                
            }
            
            $tableData["all"]['hover'] = $html['all']['hover'];
            $tableData["all"]['badge'] = $html['all']['badge'];
            $tableData["all"]['count'] = "";

            $subdata = array();
            $subdata['real_id_shipment'] = $value->id;
            #$subdata['shipment_id'] = '<a '.$marcoLink.' class="macro" data-ship-id="'.$value->id.'">'.(is_null($value->shipment_num)?$value->ex_shipment_num:$value->shipment_num)."</a>";
            #$subdata['shipment_id'] .= '<a href="javascript:void(0);" onclick="showInfo(\'' . $value->shipment_num . '\')"> <i class="fa fa-info-circle" aria-hidden="true"></i></a>';
            $subdata['shipment_id'] = '
            <div class="btn-group">
              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              '.(is_null($value->shipment_num)?$value->ex_shipment_num:$value->shipment_num).'
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item macro" href="javascript:void(0);" onclick="macroLink(\'' . $marcoLink. '\')" data-ship-id="'.$value->id.'"> Open Cargowise </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0);" onclick="showInfo(\'' . $value->shipment_num . '\')">Information <i class="fa fa-info-circle" aria-hidden="true"></i></a>
              </div>
            </div>';
            $subdata['consol_id'] = ($value->console_id==""?"No Consol ID":$value->console_id);
            $subdata['eta_date'] = '<span class="d-none">'.($eta_date_sort=="01/01/1900"?"No Date Available":$eta_date_sort).'</span> '.($eta_date=="01/01/1900"?"No Date Available":$eta_date);
            $subdata['etd_date'] = '<span class="d-none">'.($etd_date_sort=="01/01/1900"?"No Date Available":$etd_date_sort).'</span> '.($etd_date=="01/01/1900"?"No Date Available":$etd_date);
            $subdata[strtolower("all")] =  $tableData["all"]['hover'].'<div class="doc-stats">'.$tableData["all"]['badge'].$tableData["all"]['count'].'</div>';
            foreach ($doc_type as $key3 => $value3) {
                $subdata[strtolower($value3)] =  $tableData[$value3]['hover'].'<div class="doc-stats">'.$tableData[$value3]['badge'].$tableData[$value3]['count'].'</div>';
            }
            $vesselReplace = str_replace(array( '[', ']' ),'',$value->CONTAINER);
            $vesselReplace = explode(',',$vesselReplace);
            $subdata['vessel_name'] = '<a class="vesshe" href="/vessel/details?'.$vesselReplace[0].'">'.$value->vessel_name.'</a>';
            $subdata['place_of_delivery'] = $value->place_delivery;
            $subdata['consignee'] = $value->consignee;
            $subdata['consignor'] = $value->consignor;

            if(!empty($value->CONTAINER)) {
                $test = explode(':', trim($value->CONTAINER, ':'));

                // Container Number
                $container_num = array();
                foreach ($test as $keye => $valuee) {
                    $container_num[] = explode(', ', $valuee);
                }

                $subdata['container_number'] = '
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    View
                    </button>
                    <div class="dropdown-menu">';

                    if(!empty($test)) {
                        $last_key = array_key_last($container_num);
                        foreach ($container_num as $key7 => $value7) {
                            $subdata['container_number'] .= 
                            '<span class="dropdown-item">'
                            . 'Container Number: ' . $value7[0] . '<br>'
                            . 'Container Type: ' . $value7[1] . '<br>'
                            . 'Container Delivery Mode: ' . $value7[2] . '<br>'
                            . '</span>';
                            if($last_key !== $key7) {
                                $subdata['container_number'] .= '<div class="dropdown-divider"></div>';
                            }
                        }
                    }

                $subdata['container_number'] .= '
                    </div>
                </div>';
            } else {
                $subdata['container_number'] = 'No data';
            }

            $data[] = $subdata;

        }

        # Output JSON format
        echo json_encode(array("data" => $data));
    }

    // this must be change to api. temporary only
    public function addUserSettings($user=""){

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
        $Shipment = Model\User::getInstance($user);
        $data['user'] = $user;
        $data['settings']['shipment'] = $_POST['settings'];
        $data['settings']['document'] = "";
        $data['settings']['profile'] = "";
        $data['settings']['hub'] = "";
        $Shipment->addUserSettings($data);
        echo json_encode($_POST['settings']);
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
        $userData = !empty($userData)?json_decode($userData[0]->shipment):array();
        
        $defaultSettings = json_decode(file_get_contents(PUBLIC_ROOT.'/settings/shipment-settings.json'));
        
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

    public function info($user_id = "", $shipment_id = "") {

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

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        $this->View->renderWithoutHeaderAndFooter("/shipment/info", [
            "title" => "Shipment Info",
            "shipment_info" => $this->Shipment->getShipmentByShipID($shipment_id),
        ]);
    }

    public function shipmentData($user = "", $role = "") {

        if(empty($_REQUEST['order'])) {
            die('Unauthorized!');
        }

        // Check that the user is authenticated.
        //     Utility\Auth::checkAuthenticated();

        //     if (!$user) {
        //         $userSession = Utility\Config::get("SESSION_USER");
        //         if (Utility\Session::exists($userSession)) {
        //             $user = Utility\Session::get($userSession);
        //         }
        //    }

        $User = Model\User::getInstance($user);

        // // // Get an instance of the user model using the user ID passed to the
        // // // controll action. 
        // if (!$User = Model\User::getInstance($user)) {
        //     Utility\Redirect::to(APP_URL);
        // }

        $user_key = $user;
        $shipment_id = $this->Shipment->getShipment($user, "shipment_num");
        $data = $user;

        // Get the shipment by role id 
        switch ($role) {
            case 1: // SUPER ADMIN
                break;
            case 2: // CLIENT ADMIN
                break;
            case 3: // STAFF
                $user_key = $User->getSubAccountInfo($user)[0]->account_id;
                break;
            case 4: // CUSTOMER
                $org_code = Model\User::getUserInfoByID($user)[0]->organization_code;
                if($org_code == NULL) {
                    $org_code = $User->getUserContactInfo($user)[0]->organization_code;
                }
                $shipment_id = $this->Shipment->getShipmentByOrgCode($org_code, "shipment_num");
                $user_key = $User->getSubAccountInfo($user)[0]->account_id;
                $data = $org_code;
                break;
            
            default:
                die('Error in getting the user role');
                break;
        }

        $result = $this->Shipment->shipmentData($data);
        $shipment_link = $this->Shipment->getShipmentLink($user);
        $doc_type = array_column($this->Document->getDocumentType(), 'type');
        $data = $docsCollection = $json_data = $html = $tableData = $searchStore = array();

        # Advance search
        if(isset($_POST['data'][0]['value']) && $_POST['data'][0]['value'] != ""){
            // $status_search = explode(",",$_POST['status']);
            $searchResult = $this->Shipment->searchFilter($user_key, $_POST['data']);
            $result['draw'] = $_REQUEST['draw'];
            $result['recordsTotal'] = $searchResult['recordsTotal'];
            $result['recordsFiltered'] = $searchResult['recordsFiltered'];
            $result['data'] = $searchResult['data'];
        } 

        foreach($this->Document->getDocumentByShipment($shipment_id) as $key=>$value){
            $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        }

        // Get all requested document type
        $requested_doc = $this->Document->getRequestedDocument($user_key, "shipment_num, document_type");
        // Reconstruct arry object to multidimensional array
        foreach ($requested_doc as $key => $value) {
            $requested_doc[$key] = [
                'shipment_num' => $value->shipment_num,
                'document_type' => $value->document_type,
            ];
        }

        $stats = $docsCollection;

        foreach($result['data'] as $key=>$value){
            $eta_date = date_format(date_create($value->eta), "d/m/Y");
            $etd_date = date_format(date_create($value->etd), "d/m/Y");
            
            $etd_date_sort = date_format(date_create($value->etd), "d/m/Y");
            $eta_date_sort = date_format(date_create($value->eta), "d/m/Y");

            $status_arr['all']['pending2'] = 0;
            $status_arr['all']['approved2'] = 0;
            $status_arr['all']['count'] = 0;
            $status_arr["all"]["color"] = "badge-default";
            $status_arr["all"]["text"] = "Empty";
            $marcoLink = '';
            
            # Initialize macro_link from shipment_link
            foreach ($shipment_link as $key => $link) {
                if($value->shipment_num === $link->shipment_num) {
                    $marcoLink = $link->macro_link;
                }
            }
            
            # Check in document type list is empty
            if(!empty($doc_type)) {
                foreach ($doc_type as $type) {
                    $status_arr[$type]["color"] = "badge-default";
                    $status_arr[$type]["text"] = "Empty";
                    $status_arr[$type]['approved2'] = 0;
                    $status_arr[$type]['pending2'] = 0;
                }
                if(isset($stats[$value->shipment_num])) {
                    foreach ($stats[$value->shipment_num] as $key2 => $value2) {
                        if(isset($value2['pending'])) {
                            $status_arr[$value2['pending'][0]->type]['pending2'] = count($value2['pending']);
                            $status_arr['all']['pending2'] += count($value2['pending']);
                        }  
                        if(isset($value2['approved'])) {
                            $status_arr[$value2['approved'][0]->type]['approved2'] = count($value2['approved']);
                            $status_arr['all']['approved2'] += count($value2['approved']);
                        }
                        //for badge and text
                        if(isset($value2['pending'])) {
                            $status_arr[$value2['pending'][0]->type]["color"] = "badge-warning";
                            $status_arr[$value2['pending'][0]->type]["text"] = "Pending";
                            $status_arr["all"]["color"] = "badge-warning";
                            $status_arr["all"]["text"] = "View All";
                        }elseif(isset($value2['approved'])){
                            $status_arr[$value2['approved'][0]->type]["color"] = "badge-success";
                            $status_arr[$value2['approved'][0]->type]["text"] = "Approved";
                            $status_arr["all"]["color"] = "badge-success";
                            $status_arr["all"]["text"] = "View All";
                        }
                    }
                    $status_arr["all"]["color"] = "badge-primary";
                    $status_arr["all"]["text"] = "View All";
                }

                // For requested document stats
                foreach ($requested_doc as $key => $request) {
                    if($value->shipment_num == $request['shipment_num']) {
                        foreach ($doc_type as $type) {
                            if($type == $request['document_type']) {
                                $status_arr[$type]["color"] = "badge-info";
                                $status_arr[$type]["text"] = "Requested";
                            }
                        }
                    }
                }
            }


            //var_dump($status_arr); die();

            foreach($status_arr as $key => $val){
                $attr = ($key == "all" ? "" :'data-type="'.$key.'"');
                #if(in_array($key, $doc_type)){
                    $attr = ($key=="all"?"":'data-type="'.$key.'"');
                    $html[$key]['hover'] ='<div class="doc-stats" style="display: none;"><span class="doc" '.$attr.' data-id="'.$value->shipment_num.'">'.(isset($val["approved2"]) ? $val["approved2"] : 0).'<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>'.(isset($val["pending2"]) ? $val["pending2"] : 0).'<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i> 0<i class="fa fa-eye text-warning" aria-hidden="true"></i></span></div>';
                    $html[$key]['badge'] ='<span class="doc badge '.($val['color']).'" '.$attr.' data-id="'.$value->shipment_num.'">'.($val['text']).'</span>';
                    
                    if(isset($val['pending2']) && $val['pending2'] > 0){
                        $html[$key]['count'] ='<span class="badge badge-danger navbar-badge ship-badge">'.$val['pending2'].'</span>';
                    }elseif(isset($val['approved2']) && $val['approved2'] > 0){
                        $html[$key]['count'] ='<span class="badge badge-danger navbar-badge ship-badge">'.$val['approved2'].'</span>';
                    }else{
                        $html[$key]['count'] = "";
                    }
                #} else {
                // $html['all']['badge'] ='<span class="doc badge '.($val['color']).'" '.$attr.' data-id="'.$value->shipment_num.'">'.($val['text']).'</span>';
                // $html['all']['hover'] = '<div class="doc-stats" style="display: none;"><span class="doc" data-type="HBL" data-id="'.$value->shipment_num.'">0<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>0<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i> 0<i class="fa fa-eye text-warning" aria-hidden="true"></i></span></div>';
                // $html[$key]['count'] = "";
            }
            foreach($doc_type as $doc){
                if(isset($html[$doc]['hover'])){
                    $tableData[$doc]['hover'] = $html[$doc]['hover'];
                    $tableData[$doc]['badge'] = $html[$doc]['badge'];
                    if(isset($html[$doc]['count'])){
                        $tableData[$doc]['count'] =   $html[$doc]['count'];
                    }else{
                        $tableData[$doc]['count'] = "";
                    }
                }else{
                    $tableData[$doc]['hover'] = $html['all']['hover'];
                    $tableData[$doc]['badge'] = $html['all']['badge'];
                    $tableData[$doc]['count'] = "";
                }
                
            }
            
            $tableData["all"]['hover'] = $html['all']['hover'];
            $tableData["all"]['badge'] = $html['all']['badge'];
            $tableData["all"]['count'] = "";

            $subdata = array();
            $subdata['real_id_shipment'] = $value->id;
            #$subdata['shipment_id'] = '<a '.$marcoLink.' class="macro" data-ship-id="'.$value->id.'">'.(is_null($value->shipment_num)?$value->ex_shipment_num:$value->shipment_num)."</a>";
            #$subdata['shipment_id'] .= '<a href="javascript:void(0);" onclick="showInfo(\'' . $value->shipment_num . '\')"> <i class="fa fa-info-circle" aria-hidden="true"></i></a>';
            $subdata['shipment_id'] = '
            <div class="btn-group">
              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              '.(is_null($value->shipment_num)?$value->ex_shipment_num:$value->shipment_num).'
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item macro" href="javascript:void(0);" onclick="macroLink(\'' . $marcoLink. '\')" data-ship-id="'.$value->id.'"> Open Cargowise </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0);" onclick="showInfo(\'' . $value->shipment_num . '\')">Information <i class="fa fa-info-circle text-primary" aria-hidden="true"></i></a>
              </div>
            </div>';
            $subdata['consol_id'] = ($value->console_id==""?"No Consol ID":$value->console_id);
            $subdata['eta_date'] = '<span class="d-none">'.($eta_date_sort=="01/01/1900"?"No Date Available":$eta_date_sort).'</span> '.($eta_date=="01/01/1900"?"No Date Available":$eta_date);
            $subdata['etd_date'] = '<span class="d-none">'.($etd_date_sort=="01/01/1900"?"No Date Available":$etd_date_sort).'</span> '.($etd_date=="01/01/1900"?"No Date Available":$etd_date);
            $subdata[strtolower("all")] =  $tableData["all"]['hover'].'<div class="doc-stats">'.$tableData["all"]['badge'].$tableData["all"]['count'].'</div>';
            foreach ($doc_type as $key3 => $value3) {
                $subdata[strtolower($value3)] =  $tableData[$value3]['hover'].'<div class="doc-stats">'.$tableData[$value3]['badge'].$tableData[$value3]['count'].'</div>';
            }
            $vesselReplace = str_replace(array( '[', ']' ),'',$value->CONTAINER);
            $vesselReplace = explode(',',$vesselReplace);
            $subdata['vessel_name'] = '<a class="vesshe" href="/vessel/details?'.$vesselReplace[0].'">'.$value->vessel_name.'</a>';
            $subdata['place_of_delivery'] = $value->place_delivery;
            $subdata['consignee'] = $value->consignee;
            $subdata['consignor'] = $value->consignor;

            if(!empty($value->CONTAINER)) {
                $test = explode(':', trim($value->CONTAINER, ':'));

                // Container Number
                $container_num = array();
                foreach ($test as $keye => $valuee) {
                    $container_num[] = explode(', ', $valuee);
                }

                $subdata['container_number'] = '
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    View
                    </button>
                    <div class="dropdown-menu">';

                    if(!empty($test)) {
                        $last_key = array_key_last($container_num);
                        foreach ($container_num as $key7 => $value7) {
                            $subdata['container_number'] .= 
                            '<span class="dropdown-item">'
                            . 'Container Number: ' . $value7[0] . '<br>'
                            . 'Container Type: ' . $value7[1] . '<br>'
                            . 'Container Delivery Mode: ' . $value7[2] . '<br>'
                            . '</span>';
                            if($last_key !== $key7) {
                                $subdata['container_number'] .= '<div class="dropdown-divider"></div>';
                            }
                        }
                    }

                $subdata['container_number'] .= '
                    </div>
                </div>';
            } else {
                $subdata['container_number'] = 'No data';
            }


            $data[] = $subdata;

        }

        $json_data = array(
            "draw"            => $result['draw'],   
            "recordsTotal"    => $result['recordsTotal'],  
            "recordsFiltered" => $result['recordsFiltered'],
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    public function calculateDuration($date1, $date2) {
        // Declare and define two dates
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);

        // Formulate the Difference between two dates
        $diff = abs($date2 - $date1);

        // To get the year divide the resultant date into
        // total seconds in a year (365*60*60*24)
        $years = floor($diff / (365*60*60*24));

        // To get the month, subtract it with years and
        // divide the resultant date into
        // total seconds in a month (30*60*60*24)
        $months = floor(($diff - $years * 365*60*60*24)
                                        / (30*60*60*24));

        // To get the day, subtract it with years and
        // months and divide the resultant date into
        // total seconds in a days (60*60*24)
        $days = floor(($diff - $years * 365*60*60*24 -
                    $months*30*60*60*24)/ (60*60*24));

        // To get the hour, subtract it with years,
        // months & seconds and divide the resultant
        // date into total seconds in a hours (60*60)
        $hours = floor(($diff - $years * 365*60*60*24
                - $months*30*60*60*24 - $days*60*60*24)
                                            / (60*60));

        // To get the minutes, subtract it with years,
        // months, seconds and hours and divide the
        // resultant date into total seconds i.e. 60
        $minutes = floor(($diff - $years * 365*60*60*24
                - $months*30*60*60*24 - $days*60*60*24
                                    - $hours*60*60)/ 60);

        // To get the minutes, subtract it with years,
        // months, seconds, hours and minutes
        $seconds = floor(($diff - $years * 365*60*60*24
                - $months*30*60*60*24 - $days*60*60*24
                        - $hours*60*60 - $minutes*60));

        // Print the result
        printf("%d years, %d months, %d days, %d hours, "
            . "%d minutes, %d seconds", $years, $months,
                    $days, $hours, $minutes, $seconds . " ago");
    }

    public function testShipmentData() {
        // https://cargomation.com:5200/redis/getshipmentview
    }

}