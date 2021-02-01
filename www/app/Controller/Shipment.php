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

        $shipment_id = $this->Shipment->getShipment($user, "shipment_num");

        $docsCollection =array();
        foreach($this->Document->getDocumentByShipment($shipment_id) as $key=>$value){
            $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        }

        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user)->role_name;

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        // Set any dependencies, data and render the view.
        // $this->initExternals();
        // $this->View->addCSS("css/google_font.css");
        // $this->View->addCSS("css/custom.css");
        // $this->View->addJS("js/custom.js");
        $this->View->addCSS("css/shipment.css");
        $this->View->addJS("js/shipment.js");

        $this->View->renderTemplate($role, $role . "/shipment/index", [
            "title" => "Shipment",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "shipment" => $this->Shipment->getShipment($user),
            "document" => $this->Document->getDocumentByShipment($shipment_id),
            "document_per_type" => $docsCollection,
            "child_user" => Model\User::getUsersInstance($user),
            "user_settings" =>$this->defaultSettings($user)
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

        if (!$Role = Model\Role::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user_id)->role_name;

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        $this->View->addJS("js/document.js");
        $this->View->addCSS("css/document.css");

        $this->View->render($role . "/document/index", [
            "title" => "Shipment API",
            "id" => $User->data()->id,
            "email" => $User->data()->email,
            "shipment" => ["shipment_id" => $shipment_id, "type" => $type], 
            "document" => $this->Document->getDocumentByShipment($shipment_id, $type),
        ]);
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
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
        $api = json_decode(file_get_contents($protocol . $_SERVER['HTTP_HOST'] . '/api/get/shipment/uid/'.$user)); 
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

        $shipment_id = $this->Shipment->getShipment($user, "shipment_num");

        foreach($this->Document->getDocumentByShipment($shipment_id) as $key=>$value){
            $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        }
        
        $stats = $docsCollection;
        $doc_type = array('HBL','CIV','PKL','PKD','all');
        //$settings = array("Shiment ID","Console ID","ETA","HBL","CIV","PKL","PKD","ALL","Comment");
        foreach($api as $key=>$value){
            $eta_date = date_format(date_create($value->eta), "d/m/Y");
            $etd_date = date_format(date_create($value->etd), "d/m/Y");
            $all = "";
            $status_arr['all']['pending2'] = 0;
            $status_arr['all']['approved2'] = 0;
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
                        $status_arr["all"]["text"] = "Pending";
                    }elseif(isset($value2['approved'])){
                        $status_arr[$value2['approved'][0]->type]["color"] = "badge-success";
                        $status_arr[$value2['approved'][0]->type]["text"] = "Approved";
                        $status_arr["all"]["color"] = "badge-success";
                        $status_arr["all"]["text"] = "Approved";
                    }
                }
            } else {
                $status_arr["CIV"]["color"] = "badge-danger";
                $status_arr["CIV"]["text"] = "Missing";
                $status_arr["HBL"]["color"] = "badge-danger";
                $status_arr["HBL"]["text"] = "Missing";
                $status_arr["PKL"]["color"] = "badge-danger";
                $status_arr["PKL"]["text"] = "Missing";
                $status_arr["PKD"]["color"] = "badge-danger";
                $status_arr["PKD"]["text"] = "Missing";
                $status_arr["all"]["text"] = "Missing";
                $status_arr["all"]["color"] = "badge-danger";
                $status_arr['HBL']['approved2'] = 0;
                $status_arr['HBL']['pending2'] = 0;
                $status_arr['CIV']['approved2'] = 0;
                $status_arr['CIV']['pending2'] = 0;
                $status_arr['PKL']['approved2'] = 0;
                $status_arr['PKL']['pending2'] = 0;
                $status_arr['PKD']['approved2'] = 0;
                $status_arr['PKD']['pending2'] = 0;
            }
            
            $status_arr['All']['count'] = 0;
       
            foreach($status_arr as $key=>$val){
                $attr = ($key=="all"?"":'data-type="'.$key.'"');
                if(in_array($key,$doc_type)){
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
                }else{
                    $html['missing']['badge'] ='<span class="doc badge badge-danger" '.$attr.' data-id="'.$value->shipment_num.'">Missing</span>';
                    $html['missing']['hover'] = '<div class="doc-stats" style="display: none;"><span class="doc" data-type="HBL" data-id="'.$value->shipment_num.'">0<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>0<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i> 0<i class="fa fa-eye text-warning" aria-hidden="true"></i></span></div>';
                    $html[$key]['count'] = "";
                }
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
                    $tableData[$doc]['hover'] = $html['missing']['hover'];
                    $tableData[$doc]['badge'] = $html['missing']['badge'];
                    $tableData[$doc]['count'] = "";
                }
            }
            
            if(isset($status_arr['all']['pending2']) && $status_arr['all']['pending2'] > 0){
                $all.= '<span class="badge badge-danger navbar-badge ship-badge">'.$status_arr['all']['pending2'].'</span>';
            }
            
            if(!in_array($status_arr["all"]["text"],$status_search)){
              $tableData = [];
            }else{
                $marcoLink = 'href="edient:Command=ShowEditForm&amp;LicenceCode=KFRPERPER&amp;ControllerID=JobShipment&amp;BusinessEntityPK=4b2a753d-35b7-4606-8b58-c5e19d09a3f6&amp;Domain=wisecloud.zone&amp;Instance=KFRPER&amp;Hash=%2bcDgu8d3rRHVfuwhmg0HUnx2CFNfmZCjO"';
                $subdata =array();
                $subdata['real_id_shipment'] = $value->id;
                $subdata['shipment_id'] = '<a '.$marcoLink.' class="macro text-dark" data-ship-id="'.$value->id.'">'.(is_null($value->shipment_num)?$value->ex_shipment_num:$value->shipment_num)."</a>";
                $subdata['console_id'] = ($value->console_id==""?"No Console ID":$value->console_id);
                $subdata['eta'] = ($eta_date=="01/01/1900"?"No Date Available":$eta_date);
                $subdata['etd'] = ($etd_date=="01/01/1900"?"No Date Available":$etd_date);
                $subdata['hbl'] =  $tableData['HBL']['hover'].'<div class="doc-stats">'.$tableData['HBL']['badge'].$tableData['HBL']['count'].'</div>';
                $subdata['civ'] = $tableData['CIV']['hover'].'<div class="doc-stats">'.$tableData['CIV']['badge'].$tableData['CIV']['count'].'</div>';
                $subdata['pkl'] = $tableData['PKL']['hover'].'<div class="doc-stats">'.$tableData['PKL']['badge'].$tableData['PKL']['count'].'</div>';
                $subdata['pkd'] = $tableData['PKD']['hover'].'<div class="doc-stats">'.$tableData['PKD']['badge'].$tableData['PKD']['count'].'</div>';
                $subdata['all'] = $tableData['all']['hover'].'<div class="doc-stats">'.$tableData['all']['badge'].$all.'</div>';
                //$subdata['comment'] = 'No Comment';
                $subdata['vessel_name'] = $value->vessel_name;
                $subdata['place_of_delivery'] = $value->place_delivery;
                $subdata['consignee'] = $value->consignee;
                $subdata['consignor'] = $value->consignor;
                $subdata['container_number'] = $value->CONTAINER;
                
                $data[] = $subdata;
            }
        }
       
        $json_data=array(
            "data"              =>  $data,
        );
        
        echo json_encode($json_data);
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

}