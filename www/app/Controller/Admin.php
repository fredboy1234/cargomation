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
class Admin extends Core\Controller {

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

        if(Model\Role::isAdmin($User)) {
            
            // $arr = $this->View->loadResouces(PUBLIC_ROOT . "/" . "/bower_components/");
            // var_dump($arr);

            // Set any dependencies, data and render the view.
            // $dir = PUBLIC_ROOT . "/bower_components/";
            // $css_files = Utility\Scan::scanDIR($dir, ["css", "min.css"], true); 
            // $this->View->loadCSS($css_files);
            // $js_files = Utility\Scan::scanDIR($dir, ["js", "min.js"], true); 
            // Set any dependencies, data and render the view.
            // $this->View->loadCSS($js_files);
            // $this->View->addCSS("css/google_font.css");
            // $this->View->addCSS("css/custom.css");
            // $this->View->addJS("js/custom.js");

            // Render view template
            // Usage renderTemplate(string|$template, string|$filepath, array|$data)
            $this->View->renderTemplate("admin", "admin/index", [
                "title" => "Dashboard",
                "data" => (new Presenter\Profile($User->data()))->present(),
                "users" => Model\User::getUsersInstance($userID)
            ]);
        } else {
            Utility\Redirect::to(APP_URL);
        }

        // $this->View->renderTemplateTwig("/admin/index.php", [
        //     "title" => "Admin"
        // ]);
    }

    /**
     * Profile: Renders the profile view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function profile($user = "") {

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

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        // Set any dependencies, data and render the view.
        // $this->View->addCSS("css/custom.css");

        $this->View->renderTemplate("admin", "/admin/profile/index", [
            "title" => "Profile",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user)
        ]);
    }

    /**
     * Shipment: Renders the shipment view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function shipment($user = "") {

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

        if (!$Shipment = Model\Shipment::getInstance()) {
            Utility\Redirect::to(APP_URL);
        }
       
        $shipment_id = $Shipment->getShipment($user, "shipment_num");

        $Document = Model\Document::getInstance();

        // Set any dependencies, data and render the view.
        // $this->initExternals();
        // $this->View->addCSS("css/google_font.css");
        // $this->View->addCSS("css/custom.css");
        // $this->View->addJS("js/custom.js");
        $this->View->addCSS("css/shipment.css");
        $this->View->addJS("js/shipment.js");

        $docsCollection =array();
        foreach($Document->getDocumentByShipment($shipment_id) as $key=>$value){
            $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        }

        $this->View->renderTemplate("admin", "/admin/shipment/index", [
            "title" => "Shipment",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "shipment" => $Shipment->getShipment($user),
            "document" => $Document->getDocumentByShipment($shipment_id),
            "document_per_type" => $docsCollection,
            "child_user" => Model\User::getUsersInstance($user),
        ]);
    }

    /**
     * Transport: Renders the transport view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function transport($shipment_id = "") {

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

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        // Set any dependencies, data and render the view.
        // $this->initExternals();
        // $this->View->addCSS("css/google_font.css");
        // $this->View->addCSS("css/custom.css");
        // $this->View->addJS("js/custom.js");

        $this->View->renderTemplate("admin", "/admin/transport/index", [
            "title" => "Transport",
            // "data" => (new Presenter\Profile($User->data()))->present()
        ]);
    }

    /**
     * Log: Renders the log view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function log(){

        // $this->initExternals();
        // $this->View->addCSS("css/google_font.css");
        // $this->View->addCSS("css/custom.css");
        // $this->View->addJS("js/custom.js");

        $this->View->renderTemplate("admin", "/admin/logs/index", [
            "title" => "User Logs"
        ]);
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

        $Document = Model\Document::getInstance();

        $this->View->addJS("js/document.js");
        $this->View->addCSS("css/document.css");

        $this->View->render("admin/document/index", [
            "title" => "Shipment API",
            "id" => $User->data()->id,
            "email" => $User->data()->email,
            "shipment" => ["shipment_id" => $shipment_id, "type" => $type], 
            "document" => $Document->getDocumentByShipment($shipment_id, $type),
        ]);
    }

    // public function isAuthorized($User){
    //     $id = $User->data()->role;
    //     $role = Model\Role::getRole($id);

    //     if(isset($role) && $role == 'admin'){
    //         return true;
    //     }
    //     return false;
    // }

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

        $Shipment = Model\Shipment::getInstance();
        
        return $Shipment->getDocumentBySearch($post,$user);
    }

    public function addDocumentStatus(){
        $Document = Model\Document::getInstance();
        echo json_encode($Document->addDocumentStatus($_POST));
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
        $Shipment = Model\Shipment::getInstance();
        echo json_encode($Shipment->shipmentAssign($_POST,$user));
    }

    public function fileviewer($user = "", $document_id){ 

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

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        $this->View->render("admin/document/fileviewer", [
            "email" => $User->data()->email,
            "document_id" => $document_id,
        ]);
    }

    public function curl_get_contents($url) {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
        $ch = curl_init($protocol . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function shipmentSSR($user=''){
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
            }
        }

        $Shipment = Model\Shipment::getInstance();
        $shipment_id = $Shipment->getShipment($user, "shipment_num");
        $Document = Model\Document::getInstance();
        foreach($Document->getDocumentByShipment($shipment_id) as $key=>$value){
            $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        }

        $stats = $docsCollection;
        $doc_type = array('HBL','CIV','PKL','PKD','all');
        //$settings = array("Shiment ID","Console ID","ETA","HBL","CIV","PKL","PKD","ALL","Comment");
        foreach($api as $key=>$value){
            $eta_date = date_format(date_create($value->eta), "m/d/Y H:i:s");
            $etd_date = date_format(date_create($value->etd), "m/d/Y H:i:s");
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
                $subdata =array();
                $subdata['shipment_id'] = (is_null($value->shipment_num)?$value->ex_shipment_num:$value->shipment_num);
                $subdata['console_id'] = ($value->console_id==""?"No Console ID":$value->console_id);
                $subdata['eta'] = ($eta_date=="01/01/1900 00:00:00"?"No Date Available":$eta_date);
                $subdata['eda'] = ($etd_date=="01/01/1900 00:00:00"?"No Date Available":$etd_date);
                $subdata['hbl'] =  $tableData['HBL']['hover'].'<div class="doc-stats">'.$tableData['HBL']['badge'].$tableData['HBL']['count'].'</div>';
                $subdata['civ'] = $tableData['CIV']['hover'].'<div class="doc-stats">'.$tableData['CIV']['badge'].$tableData['CIV']['count'].'</div>';
                $subdata['pkl'] = $tableData['PKL']['hover'].'<div class="doc-stats">'.$tableData['PKL']['badge'].$tableData['PKL']['count'].'</div>';
                $subdata['pkd'] = $tableData['PKD']['hover'].'<div class="doc-stats">'.$tableData['PKD']['badge'].$tableData['PKD']['count'].'</div>';
                $subdata['all'] = $tableData['all']['hover'].'<div class="doc-stats">'.$tableData['all']['badge'].$all.'</div>';
                $subdata['comment'] = 'No Comment';
                $data[] = $subdata;
            }
        }
       
        $json_data=array(
            "data"              =>  $data,
        );

        echo json_encode($json_data);
    }

}