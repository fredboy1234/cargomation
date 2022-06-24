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
        }// echo "<pre>";
        // exit();
        $this->View->renderTemplate("/shipment/index", [
            "title" => "Shipment View",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => $imageList,
            "notifications" => Model\User::getUserNotifications($user),
            "menu" => Model\User::getUserMenu($user, $role->role_id),
            "user_settings" =>$this->defaultSettings($user, $role->role_id), // $user_key??
            "settings_user" => $selectedTheme,
            "image_profile" => $profileImage,
            'role' => $role,
            'user_id' => $user,
            'selected_theme' => $selectedTheme,
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

        // $document_type = "";
        // // get client admin email
        // if(!empty($User->getSubAccountInfo($user_id))) {
        //     $sub_account = $User->getSubAccountInfo($user_id);
        //     // "user email" change to "client email"
        //     $email = $sub_account[0]->client_email;
        //     $sub_id = array('user_id' => $sub_account[0]->user_id,
        //                     'client_id' => $sub_account[0]->account_id);
        //     $document_type = $this->Document->getDocumentTypeByUserID($sub_id);
        // }

        $document_type = $this->Document->getDocumentTypeByUserID($user_id);
        $client_doc_type = $User->getClientDocumentType($user_id);

        if(empty($type)) {
            $type = [];
            foreach ($this->client_doc_type as $key => $value) {
                $type[] = $value->doc_type;
            }
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
            "document" => $this->Document->getDocumentByShipment2($shipment_id, $type),
            "document_type" => $document_type,
            "user_settings" => $User->getUserSettings($user_id)
        ]);
    }

    // this must be change to api. temporary only
    public function addUserSettings($column = 'shipment', $user=""){
        $User = new Model\User;
        switch ($column) {
            case 'shipment':
                $data = $this->sanitizeSettings($User, $_POST);
                break;
            case 'column-order':
                $data = $this->columnOrder($User, $_POST);
                $column = 'shipment';
                break;
            case 'document':
                
                break;
            default:
                # code...
                break;
        }

        $User->addUserSettings($column, $_POST['user_id'], $data);
        // echo json_encode($_POST['settings']);
        echo $data;
    }

    public function sanitizeSettings($User, $data) {

        // DEFAULT SHIPMENT COLUMN SETTTING
        $json_setting = '/settings/sub-shipment-settings.json';
        $defaultSettings = json_decode(file_get_contents(PUBLIC_ROOT.$json_setting));

        $shipment_settings = [];

        // SHIPMENT COLUMN NEEDS TO SHOW
        $need_show = empty($data['data']) ? [] : $data['data'];
        foreach($defaultSettings->table  as $key=> $value){
            $value->index_check = 'false';
            if(in_array($value->index, $need_show)){
                $value->index_check = 'true';
            }
            $shipment_settings[] = $value;
        }

        // START COUNT FOR DOCUMENT TYPE
        $count = count($shipment_settings);

        // DEFAULT DOCUMENT COLUMN SETTING (CLIENT ADMIN)
        // $doc_type = $User->getCWDOcumentType($data['user_id']);
        $doc_type = $User->getClientDocumentType($data['user_id']);
        $selected_doc = [];
        $sub_account = $User->getSubAccountInfo($data['user_id']);
        if($sub_account[0]->role_id > 2) {
            $document_settings = json_decode($User->getSubDocumentType($data['user_id'], $sub_account[0]->account_id)[0]->shipment);
            if(!is_null($document_settings)) {
                foreach ($document_settings as $key => $value) {
                    if($value->index_lvl == 'document') {
                        $selected_doc[] = strtoupper($value->index);
                    }
                }
            }

            // DOCUMENT COLUMN NEEDS TO SHOW
            if(!empty($doc_type)){
                foreach ($doc_type as $key => $value) {
                    if(in_array($value->doc_type, $selected_doc)){
                        if(in_array(strtolower($value->doc_type), $need_show)){
                            array_push($shipment_settings, (object)[
                                'index' => strtolower($value->doc_type),
                                'index_name' => $value->doc_type . " - " . $value->description,
                                // 'index_value' => (string)$count++, // Explicit cast
                                'index_value' => strval($count++), // Function call
                                'index_check' => 'true',
                                'index_lvl' => 'document',
                                'index_sortable' => 'false'
                            ]);
                        } else {
                            array_push($shipment_settings, (object)[
                                'index' => strtolower($value->doc_type),
                                'index_name' => $value->doc_type . " - " . $value->description,
                                // 'index_value' => (string)$count++, // Explicit cast
                                'index_value' => strval($count++), // Function call
                                'index_check' => 'false',
                                'index_lvl' => 'document',
                                'index_sortable' => 'false'
                            ]);
                        }
                    }
                }
            } 
        } else {
            // DOCUMENT COLUMN NEEDS TO SHOW
            if(!empty($doc_type)){
                foreach ($doc_type as $key => $value) {
                    if(in_array(strtolower($value->doc_type), $need_show)){
                        array_push($shipment_settings, (object)[
                            'index' => strtolower($value->doc_type),
                            'index_name' => $value->doc_type . " - " . $value->description,
                            // 'index_value' => (string)$count++, // Explicit cast
                            'index_value' => strval($count++), // Function call
                            'index_check' => 'true',
                            'index_lvl' => 'document',
                            'index_sortable' => 'false'
                        ]);
                    } else {
                        array_push($shipment_settings, (object)[
                            'index' => strtolower($value->doc_type),
                            'index_name' => $value->doc_type . " - " . $value->description,
                            // 'index_value' => (string)$count++, // Explicit cast
                            'index_value' => strval($count++), // Function call
                            'index_check' => 'false',
                            'index_lvl' => 'document',
                            'index_sortable' => 'false'
                        ]);
                    }
                }
            } 
        }
        return json_encode($shipment_settings);
    }

    public function defaultSettings_OLD($user="", $role_id=""){

        $User = Model\User::getInstance($user);
        $userData = $User->getUserSettings($user);
        $userData = !isset($userData)?json_decode($userData[0]->shipment):array();

        // if ($role_id == 4) {
        //     // customer
        //     $sub_account = $User->getSubAccountInfo($user);
        //     $org_code = Model\User::getUserInfoByID($user)[0]->organization_code;
        //     $doc_type = $User->getUserDocumentType($sub_account[0]->account_id, $role_id, $org_code); // $this->Document->getDocumentType(), 'type');
        // } else if ($role_id == 3) {
        //     // staff 
        //     $sub_account = $User->getSubAccountInfo($user);
        //     $doc_type = $User->getUserDocumentType($sub_account[0]->account_id, $role_id);
        // } else {
        //     // client admin
        //     $doc_type = $User->getUserDocumentType($user, $role_id);
        // }

        $doc_type = $User->getCWDOcumentType($user);

        // $Role = Model\Role::getInstance($user);
        // $role = $Role->getUserRole($user);
        // $role = Model\Role::getInstance($user_id)->getUserRole($user_id);

        // $json_setting = '/settings/shipment-settings.json';

        // if($role_id == 4 && empty($doc_type)) {
            $json_setting = '/settings/sub-shipment-settings.json';
        // }

        $defaultSettings = json_decode(file_get_contents(PUBLIC_ROOT.$json_setting));
        
        $defaultCollection = array();
        if(isset($userData) && !empty($userData)){
            foreach($userData as $key => $value){
                $defaultCollection[]=$value->index_value;
            }
        }
        // $defaultDocType = ['PKD', 'PKL', 'HBL', 'MBL', 'COO', 'CIV'];
        if(!empty($doc_type)){
            $count = 11;
            foreach ($doc_type as $key => $value) {
                // if(!in_array($value->type,$defaultDocType)){
                    array_push($userData, (object)[
                        'index' => strtolower($value->doc_type),
                        'index_name' => $value->doc_type . " - " . $value->description,
                        // 'index_value' => (string)$count++, // Explicit cast
                        'index_value' => strval($count++), // Function call
                        'index_check' => 'false',
                        'index_lvl' => 'document',
                        'index_sortable' => 'false'
                    ]);
                }
            // }
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

        if(!empty($User->getUserSettings($user)[0]->shipment)){
            return $User->getUserSettings($user)[0]->shipment;
        }else{
            return json_encode($userData);    
        }

    }

    public function defaultSettings($user_id = "", $role_id="") {
        $User = Model\User::getInstance($user_id);
        $settings = $User->getUserSettings($user_id);

        if(is_null($settings[0]->shipment)) {
            // DEFAULT SHIPMENT COLUMN SETTTING
            $json_setting = '/settings/sub-shipment-settings.json';
            $defaultSettings = json_decode(file_get_contents(PUBLIC_ROOT.$json_setting));

            $shipment_settings = [];

            // SHIPMENT COLUMN NEEDS TO SHOW
            // $need_show = empty($data['data']) ? [1] : $data['data'];
            foreach($defaultSettings->table  as $key=> $value){
                // if(in_array($value->index_value, $need_show)){
                //     $value->index_check = 'true';
                // } else {
                //     $value->index_check = 'false';
                // }
                $shipment_settings[] = $value;
            }

            // START COUNT FOR DOCUMENT TYPE
            $count = count($shipment_settings);

            // DEFAULT DOCUMENT COLUMN SETTING (CLIENT ADMIN)
            // $doc_type = $User->getCWDOcumentType($data['user_id']);
            $doc_type = $User->getClientDocumentType($user_id);
            $selected_doc = [];
            $sub_account = $User->getSubAccountInfo($user_id);
            if($sub_account[0]->role_id > 2) {
                $document_settings = json_decode($User->getSubDocumentType($user_id, $sub_account[0]->account_id)[0]->shipment);
                if(!is_null($document_settings)) {
                    foreach ($document_settings as $key => $value) {
                        if($value->index_lvl == 'document') {
                            $selected_doc[] = strtoupper($value->index);
                        }
                    }
                }
            }

            // DOCUMENT COLUMN NEEDS TO SHOW
            if(!is_null($doc_type)){
                foreach ($doc_type as $key => $value) {
                    array_push($shipment_settings, (object)[
                        'index' => strtolower($value->doc_type),
                        'index_name' => $value->doc_type . " - " . $value->description,
                        // 'index_value' => (string)$count++, // Explicit cast
                        'index_value' => strval($count++), // Function call
                        'index_check' => 'true',
                        'index_lvl' => 'document',
                        'index_sortable' => 'false'
                    ]);
                }
            } 
            $result = json_encode($shipment_settings);
        } else {
            $result = $settings[0]->shipment;
        }

        return $result;
    }

    public function columnOrder($User, $data) {
        return json_encode($data['data']);
    }

    public function info($user_id = "", $shipment_num = "") {

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

        $shipment_info = $this->Shipment->getShipmentByShipNum($shipment_num);
        $container_detail = $this->Shipment->getContainerByShipNum($shipment_num);
        $shipment_contact = $this->Shipment->getShipmentContactByShipID($shipment_info[0]->id);

        $this->View->renderWithoutHeaderAndFooter("/shipment/info", [
            "title" => "Shipment Info",
            "shipment_info" => $shipment_info,
            "container_detail" => $container_detail,
            "shipment_contact" => $shipment_contact
        ]);
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

    public function shipmentData($user_id = "") {
       
        if(!isset($_POST['draw'])) {
            die('Unauthorized Access');
        }
         //this code is temporary remove this if kuya has API
         $requested = '';
         
         if(isset($_POST['data'][0]['value']) && $_POST['data'][0]['value'] ==='REQUESTED'){
             //unset($_POST);
             unset($_POST['data']);
             $requested = 'requested';
         }

         if(isset($_POST['data'][0]['value']) && $_POST['data'][0]['value'] ==='NEWSHIPMENTS'){
            //unset($_POST);
            unset($_POST['data']);
            $requested = 'newshipments';
        }
        
        // Check that the user is authenticated.
        //Utility\Auth::checkAuthenticated();
        //if (!$user_id) {
        //    $userSession = Utility\Config::get("SESSION_USER");
        //    if (Utility\Session::exists($userSession)) {
        //        $user_id = Utility\Session::get($userSession);
        //    }
        //}
        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        //if (!$User = Model\User::getInstance($user_id)) {
        //    Utility\Redirect::to(APP_URL);
        //}
        $User = new Model\User;
        $url = 'https://cargomation.com:5200/redis/getshipmentview';
        $arr = [
            "draw" => $_POST['draw'],
            "user_id" => $user_id,
            "length" => (is_numeric($_POST['length']) ? (int)$_POST['length'] : 0),
            "start" => (is_numeric($_POST['start']) ? (int)$_POST['start'] : 0),
        ];
        
       
        if(isset($_POST['order'][0]['column']) && $_POST['order'][0]['dir'] != "") {
            foreach ($_POST['order'] as $key => $value) {
                // change shipment_id to shipment_num
                if($_POST['order'][0]['column'] == 0) {
                    $_POST['columns'][$_POST['order'][0]['column']]['data'] = "id";
                }
                $arr["sort"] = array((object)["order" => $_POST['columns'][$_POST['order'][0]['column']]['data'],
                                "by" => $value['dir']]);
            }
        }
        if(isset($_POST['search']['value']) && !empty($_POST['search']['value'])) {
            $arr["filter"] = array((object)["columnname" => "shipment_num",
                                            "type" => "contains",
                                            "value" => $_POST['search']['value'],
                                            "cond" => ""]);
        }
        if(!empty($_POST['data'][0]['columnname']) && !empty($_POST['data'][0]['value'])) {
            $arr["filter"] = $_POST['data'];
            $User->putRecentSearch($user_id, $arr["filter"]);
        }
        $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
        $headers = ["Authorization: Basic YWRtaW46dVx9TVs2enpBVUB3OFlMeA==",
                    "Content-Type: application/json"];
                 
        $result = $this->post($url, $payload, $headers);
        $json_data = json_decode($result);
        if($json_data->status != '200' || empty($json_data)) {
            echo json_encode($json_data);
            exit;
        }
        $array_data = array(
            "draw"            => $json_data->draw,  
            "recordsTotal"    => $json_data->recordsTotal,  
            "recordsFiltered" => $json_data->recordsTotal,
            "data"            => $this->sanitizeData($user_id, $json_data->data, $requested)
        );
        echo json_encode($array_data);
    }

    //must remove the $requested parameter if Kuya has the API
    private function sanitizeData($user_id, $param, $docRequest) {
        $array_data = json_decode($param);
        $User = new Model\User;
        // $doc_type = array_column($this->Document->getDocumentType(), 'type');
        $doc_type = array_column($User->getCWDOcumentType($user_id), 'doc_type');
        $data = $docsCollection = $json_data = $html = $tableData = $searchStore = array();
        $documents = array();
      
        foreach($array_data as $shipment_key => $shipment) {
            $eta_date = date_format(date_create($shipment->eta), "d/m/Y");
            $etd_date = date_format(date_create($shipment->etd), "d/m/Y");
            $etd_date_sort = date_format(date_create($shipment->eta), "d/m/Y");
            $eta_date_sort = date_format(date_create($shipment->etd), "d/m/Y");
            $ata_date = date_format(date_create($shipment->ata), "d/m/Y");
            $atd_date = date_format(date_create($shipment->atd), "d/m/Y");
            $atd_date_sort = date_format(date_create($shipment->ata), "d/m/Y");
            $ata_date_sort = date_format(date_create($shipment->atd), "d/m/Y");
            $sta_date = '';
            $marco_link = "";
            $etadays = "";

            
            if(isset($shipment->route_leg) && !empty($shipment->route_leg)){
                $stadecode= json_decode($shipment->route_leg);
                if(isset($stadecode[0]->ScheduledArrival) && !is_array($stadecode[0]->ScheduledArrival)){
                   
                    $sta_date = date_format(date_create($stadecode[0]->ScheduledArrival), "Y-m-d");
                    $diff =  strtotime(date_format(date_create($shipment->eta), "Y-m-d")) - strtotime($sta_date);
                    $etadiff = ceil($diff / 86400);
                    $etadaycolor = '';
                    if($sta_date !=="" || strpos($shipment->eta,"1900-01-01")===false){
                        if($etadiff > 0){
                            $etadays =' <span style="color:red;" class="badge navbar-badge ship-badge">+'.$etadiff.'d</span>';
                        }else{
                            if($etadiff != 0){
                                $etadays =' <span style="color:green;" class="badge navbar-badge ship-badge">'.$etadiff.'d</span>';
                            }
                        }
                    }else{
                        $etadays='';
                    }
                }
            }
            
            

            if(!empty($shipment->vrptShipmentlinks)) {
                $marco_link = $shipment->vrptShipmentlinks[0]->macro_link;
            }

            $subdata = array();
            $subdata['real_id_shipment'] = $shipment->shipment_num; // remove?
            $subdata['id'] = $shipment->id; // remove?
            $subdata['shipment_num'] = '
            <div class="btn-group">
              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              '.(is_null($shipment->shipment_num) ? "0000" : $shipment->shipment_num).'
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item macro" href="javascript:void(0);" onclick="macroLink(\''.$marco_link.'\')" data-ship-id="'.$shipment->id.'"> Open Cargowise </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0);" onclick="showInfo(\'' . $shipment->shipment_num . '\')">Information <i class="fa fa-info-circle text-primary" aria-hidden="true"></i></a>
              </div>
            </div>';
            $subdata['console_id'] = (empty($shipment->console_id)) ? '<span class="text-warning">No Consol ID</span>' : $shipment->console_id;
            $subdata['eta_date'] = '<span class="d-none">'.($eta_date_sort=="01/01/1900"?"No Date Available":$eta_date_sort).'</span>'.($eta_date=='01/01/1900'?'<span class="text-warning">No Date Available</span>':$eta_date).$etadays;
            $subdata['etd_date'] = '<span class="d-none">'.($etd_date_sort=="01/01/1900"?"No Date Available":$etd_date_sort).'</span>'.($etd_date=='01/01/1900'?'<span class="text-warning">No Date Available</span>':$etd_date);
            $subdata['vessel_name'] = (empty($shipment->vessel_name)) ? '<span class="text-warning">No Data</span>' : $shipment->vessel_name;
            $subdata['place_delivery'] = $shipment->place_delivery;
            $subdata['consignee'] = $shipment->consignee;
            $subdata['consignor'] = $shipment->consignor;
            // Additionals
            $subdata['master_bill'] = $shipment->master_bill;
            $subdata['house_bill'] = $shipment->house_bill;
            $subdata['transport_mode'] = $shipment->transport_mode;
            $subdata['voyage_flight_num'] = $shipment->voyage_flight_num;
            $subdata['container_mode'] = $shipment->container_mode;
            $subdata['port_loading'] = $shipment->port_loading;
            $subdata['port_discharge'] = $shipment->port_discharge;
            // $subdata['order_number'] = $shipment->order_number;
            $order_number = json_decode($shipment->order_number); 
            if(!empty($order_number)){
                if(!is_array($order_number)) {
                    $subdata['order_number'] = $order_number;
                } else {
                    if(count($order_number) == 1) {
                        $subdata['order_number'] = $order_number[0]->OrderReference;
                    } else {
                        $subdata['order_number'] = '<div class="btn-group">
                        <button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        $subdata['order_number'] .= $order_number[0]->OrderReference . 
                        '</button>';
                        $subdata['order_number'] .= '<div class="dropdown-menu">';
                        $last_key1 = array_key_last($order_number);
                        foreach ($order_number as $key => $order) {
                            $subdata['order_number'] .=  '<span class="dropdown-item">' . $order->OrderReference . '</span>';
                            if($last_key1 !== $key) {
                                $subdata['order_number'] .= '<div class="dropdown-divider"></div>';
                            }
                        }
                        $subdata['order_number'] .= '</div></div>';
                    }
                }
            } else {
                $subdata['order_number'] = "-";
            }
            $subdata['ata_date'] = '<span class="d-none">'.($ata_date_sort=="01/01/1900"?"No Date Available":$ata_date_sort).'</span>'.($ata_date=='01/01/1900'?'<span class="text-warning">No Date Available</span>':$ata_date);
            $subdata['atd_date'] = '<span class="d-none">'.($atd_date_sort=="01/01/1900"?"No Date Available":$atd_date_sort).'</span>'.($atd_date=='01/01/1900'?'<span class="text-warning">No Date Available</span>':$atd_date);
            if(!empty($shipment->Containers)) {
                $test = explode(':', trim($shipment->Containers[0]->CONTAINER, ':'));
                // Container Number
                $container_num = array();
                foreach ($test as $keye => $valuee) {
                    $container_num[] = explode(', ', $valuee);
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
                }
            } else {
                $subdata['container_number'] = '<span class="text-warning">No data</span>';
            }
            // DOCUMENT LEVEL
            $subdata['all'] = (empty($shipment->Documents)) ? '<div class="doc-stats">
            <span class="doc text-warning no-doc" data-id="' . $shipment->shipment_num . '">No Document</span></div>' :'<div class="doc-stats">
            <span class="doc badge badge-primary" data-id="' . $shipment->shipment_num . '">View All</span></div>';
            // Default Empty Value (DEV)
            foreach ($doc_type as $type) {
                $documents[strtolower($type)]['text'] = "Empty";
                $documents[strtolower($type)]['approved'] = 0;
                $documents[strtolower($type)]['pending'] = 0;
                $documents[strtolower($type)]['watched'] = 0;
                $documents[strtolower($type)]['badge'] = "";
                $documents[strtolower($type)]['count'] = "";
            }
            if(!empty($shipment->Documents)) {
                foreach ($shipment->Documents as $document_key => $document) {
                    // Status Count
                    if($document->status == "approved") {
                        $documents[strtolower($document->type)]['approved']++;
                    }
                    if($document->status == "pending") {
                        $documents[strtolower($document->type)]['pending']++;
                    }  
                    if($document->status == "watched") {
                        $documents[strtolower($document->type)]['watched']++;
                    }
                    // Status Text and Badge
                    if($documents[strtolower($document->type)]['pending'] < $documents[strtolower($document->type)]['approved']) {
                        $documents[strtolower($document->type)]['count'] = $documents[strtolower($document->type)]['approved'];
                        $documents[strtolower($document->type)]['badge'] = "badge-success";
                        $documents[strtolower($document->type)]['text'] = "Approved"; 
                    } elseif($documents[strtolower($document->type)]['pending'] > $documents[strtolower($document->type)]['approved']) {
                        $documents[strtolower($document->type)]['count'] = $documents[strtolower($document->type)]['pending'];
                        $documents[strtolower($document->type)]['badge'] = "badge-warning";
                        $documents[strtolower($document->type)]['text'] = "Pending";
                    } else {
                        // $documents[strtolower($document->type)]['count'] = $documents[strtolower($document->type)]['pending'];
                        // $documents[strtolower($document->type)]['badge'] = "badge-warning";
                        // $documents[strtolower($document->type)]['text'] = "Pending";
                    }
                }
            }
            // DOCUMENT REQUEST
            if(!empty($shipment->DocumentRequests)) {
                foreach ($shipment->DocumentRequests as $requested_key => $requested) {
                    // $requested->shipment_num // $requested->document_type // $requested->document_id 
                    // $requested->request_type // $requested->expired_date // $requested->status
                    // $requested->sender
                    if(strpos($requested->document_type, ',') === false) {
                        $documents[strtolower($requested->document_type )]['count'] = $requested->request_type;
                        $documents[strtolower($requested->document_type )]['badge'] = "badge-info";
                        $documents[strtolower($requested->document_type )]['text'] = "Requested"; 
                        // If type already have a document
                        if(!isset($documents[strtolower($requested->document_type )]['approved'])) {
                            $documents[strtolower($requested->document_type )]['approved'] = 0;
                        }
                        if(!isset($documents[strtolower($requested->document_type )]['pending'])) {
                            $documents[strtolower($requested->document_type )]['pending'] = 0;
                        }
                        if(!isset($documents[strtolower($requested->document_type )]['watched'])) {
                            $documents[strtolower($requested->document_type )]['watched'] = 0;
                        }
                    } else {
                        $doc_array = explode(",", $requested->document_type);
                        foreach ($doc_array as $key => $value) {
                            $documents[strtolower($value)]['count'] = $requested->request_type;
                            $documents[strtolower($value)]['badge'] = "badge-info";
                            $documents[strtolower($value)]['text'] = "Requested"; 
                            // If type already have a document
                            if(!isset($documents[strtolower($value)]['approved'])) {
                                $documents[strtolower($value)]['approved'] = 0;
                            }
                            if(!isset($documents[strtolower($value)]['pending'])) {
                                $documents[strtolower($value)]['pending'] = 0;
                            }
                            if(!isset($documents[strtolower($value)]['watched'])) {
                                $documents[strtolower($value)]['watched'] = 0;
                            }
                        }
                    }
                }
            }
            foreach ($documents as $key => $value) {
                #if(!empty($value['count'])) {
                    $subdata[$key] = '<div class="doc-stats" style="display: none;">
                    <span class="doc" data-type="'.strtoupper($key).'" data-id="'.$shipment->shipment_num.'">
                    '.$value['approved'].'<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                    '.$value['pending'].'<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i> 
                    '.$value['watched'].'<i class="fa fa-eye text-warning" aria-hidden="true"></i>
                    </span>
                    </div>
                    <div class="doc-stats">
                        <span class="doc badge '.$value['badge'].'" data-type="'.strtoupper($key).'" data-id="'.$shipment->shipment_num.'">'.$value['text'].'</span>
                        <span class="badge badge-danger navbar-badge ship-badge">'.$value['count'].'</span>
                    </div>';
                #} else {
                    # $subdata[$key] = "Empty";
                #}
            }

            //remove this code if kuya has API
            // print_r($docRequest);
            // exit;
            if($docRequest ==='requested'){
                if(!empty($shipment->Documents)) {
                    $data[] = $subdata;
                }
            }elseif($docRequest ==='newshipments'){
                $etdata = date("Y-m-d", strtotime($shipment->eta));
                $now_date = date("Y-m-d");
                if($etdata >= $now_date) {
                    $data[] = $subdata;
                }
            }else{
                $data[] = $subdata;
            }

            //uncomment this if kuya has API
            // $data[] = $subdata;
            
        }
        return $data;
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

    function getCity(){
        if(isset($_POST)){
            //$cities = json_decode(file_get_contents(PUBLIC_ROOT.'/settings/cityinfo.json'));
            $cities =  $this->Shipment->getCity($_POST['location']); 
            echo json_encode($cities);
        }
    }

    public function getOrgCodeByUserID($user_id = "") {
        $User = Model\User::getInstance($user_id);
        $test = $User->getOrgCodeByUserID($user_id);
        echo json_encode($test);
    }

    public function putSaveSearch() {
        $user_id = $_POST['user_id'];
        $search_title = $_POST['search_title'];
        $data = $_POST['search'];
        $User = Model\User::getInstance($user_id);
        $test = $User->putSaveSearch($user_id, $search_title, $data);
        echo json_encode($test);
    }

    public function getRecentSave() {
        $user_id = $_POST['user_id'];
        $User = Model\User::getInstance($user_id);
        $test = $User->getSaveSearch($user_id);
        if(!empty($test)) {
            echo json_encode($test[0]);
        }
    }

    public function deleteSaveSearch() {
        $user_id = $_POST['user_id'];
        $id = $_POST['id'];
        $User = Model\User::getInstance($user_id);
        $result = $User->deleteSaveSearch($user_id, $id);
        echo json_encode($result);
    }

    public function updateSaveSearch() {
        $user_id = $_POST['user_id'];
        $search_title = $_POST['search_title'];
        $data = $_POST['search'];
        $id = $_POST['id'];
        $User = Model\User::getInstance($user_id);
        $test = $User->updateSaveSearch($user_id, $search_title, $data, $id);
        echo json_encode($test);
    }

    public function setDefaultSearch() {
        
    }

    public function request($shipment_id = "", $doc_type = "", $requestToken = "") {

        if(isset($requestToken) && !empty($requestToken)) {
            $Document = new Model\Document();
            $checkStatus = $Document->getRequestedStatus($requestToken);

            if(!empty($checkStatus)) { // check if result array is empty
                if(is_null($checkStatus[0]->status)) {
                    $Document->putRequestedStatus("opened", $requestToken);
                } else {
                    $requestDocument = $Document->getRequestedDocumentByToken($requestToken);
                    $expired_date = date("Y-m-d H:i:s", strtotime($requestDocument[0]->expired_date));
                    $now_date = date("Y-m-d H:i:s");

                    if ($expired_date < $now_date)
                        die("Your request token has expired!");
                }
            } else {
                echo "Can't process the token. Please contact administrator.";
                echo '<br> Go to <a href="/dashboard">dashboard</a>';
                die();
            }

            if(strpos($doc_type,',') != false) {
                $doc_type = '';
            }

            $redirectLink = 'shipment?request=true&shipment_num='.$shipment_id.'&type='.$doc_type;

            Utility\Cookie::put("redirectLink", $redirectLink, 3600);
            Utility\Cookie::put("requestToken", $requestToken, 3600);

            // Check that the user is authenticated.
            Utility\Auth::checkUnauthenticated($redirectLink);

            Utility\Redirect::to(APP_URL . 'login?redirect=shipment');

        } else {
            echo "Invalid request token!";
        }
    }
}
