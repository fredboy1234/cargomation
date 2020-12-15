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

    public function advanceSearch($user =''){
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
        echo json_encode($Shipment->getDocumentBySearch($_POST,$user));
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
         Utility\Auth::checkAuthenticated();

         if (!$user) {
             $userSession = Utility\Config::get("SESSION_USER");
             if (Utility\Session::exists($userSession)) {
                 $user = Utility\Session::get($userSession);
             }
         }
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
        $api = json_decode(file_get_contents($protocol . $_SERVER['HTTP_HOST'] . '/api/get/shipment/sid/'.$user)); 
        $Shipment = Model\Shipment::getInstance();
        $shipment_id = $Shipment->getShipment($user, "shipment_num");
        $Document = Model\Document::getInstance();
        foreach($Document->getDocumentByShipment($shipment_id) as $key=>$value){
            $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        }
        $stats = $docsCollection;
        $doc_type = array('HBL','CIV','PKL','PKD');
        foreach($api as $key=>$value){
            $hbl = "";
            $civ ="";
            $pkl="";
            $pkd = "";
            $status_arr['all']['pending'] = 0;
            $status_arr['all']['approved'] = 0;
            if(isset($stats[$value->shipment_num])) {
                foreach ($stats[$value->shipment_num] as $key2 => $value2) {
                    if(isset($value2['pending'])) {
                        $status_arr[$value2['pending'][0]->type]['pending2'] = count($value2['pending']);
                        $status_arr['all']['pending'] += count($value2['pending']);
                    }  
                    if(isset($value2['approved'])) {
                        $status_arr[$value2['approved'][0]->type]['approved2'] = count($value2['approved']);
                        $status_arr['all']['approved'] += count($value2['approved']);
                    }
                }
            } else {
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
       
            foreach($doc_type as $doc){
                if(isset($stats[$value->shipment_num])){
                    if(isset($stats[$value->shipment_num][$doc]['pending'])){
                        $status_arr[$doc]['color'] = "badge-warning";
                        $status_arr[$doc]['text'] = "Pending";
                        $status_arr[$doc]['count']= count($stats[$value->shipment_num][$doc]['pending']); 
                        
                        $status_arr['All']['color'] = 'badge-warning';
                        $status_arr['All']['text'] = 'Pending';
                        $status_arr['All']['count'] += $status_arr[$doc]['count'];
                    }else if(isset($stats[$value->shipment_num][$doc]['approved'])){
                        $status_arr[$doc]['color'] = "badge-success";
                        $status_arr[$doc]['text'] = "Approved";
                        $status_arr[$doc]['count']= count($stats[$value->shipment_num][$doc]['approved']);
                        
                        $status_arr['All']['color'] = 'badge-success';
                        $status_arr['All']['text'] = 'Approved';
                        $status_arr['All']['count'] += $status_arr[$doc]['count'];
                    }else{
                        $status_arr[$doc]['color'] = "badge-danger";
                        $status_arr[$doc]['text'] = "Missing";
                        $status_arr[$doc]['count'] = 0;
                       
                    }
                }else{
                    $status_arr[$doc]['color'] = "badge-danger";
                    $status_arr[$doc]['text'] = "Missing";
                    $status_arr[$doc]['count'] = 0;
            
                    $status_arr['All']['color'] = 'badge-danger';
                    $status_arr['All']['text'] = 'Missing';
                    $status_arr['All']['count'] = 0;
                }
            }
            $hblF = '<div class="doc-stats" style="display: none;"><span class="doc" data-type="HBL" data-id="'.$value->shipment_num.'">'.(isset($status_arr["HBL"]["approved2"]) ? $status_arr["HBL"]["approved2"] : 0).'<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>'.(isset($status_arr["HBL"]["pending2"]) ? $status_arr["HBL"]["pending2"] : 0).'<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i> 0<i class="fa fa-eye text-warning" aria-hidden="true"></i></span></div>';
            $civF = '<div class="doc-stats" style="display: none;"><span class="doc" data-type="CIV" data-id="'.$value->shipment_num.'">'.(isset($status_arr["CIV"]["approved2"]) ? $status_arr["CIV"]["approved2"] : 0).'<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>'.(isset($status_arr["CIV"]["pending2"]) ? $status_arr["CIV"]["pending2"] : 0).'<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i> 0<i class="fa fa-eye text-warning" aria-hidden="true"></i></span></div>';
            $pklF = '<div class="doc-stats" style="display: none;"><span class="doc" data-type="PKL" data-id="'.$value->shipment_num.'">'.(isset($status_arr["PKL"]["approved2"]) ? $status_arr["PKL"]["approved2"] : 0).'<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>'.(isset($status_arr["PKL"]["pending2"]) ? $status_arr["PKL"]["pending2"] : 0).'<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i> 0<i class="fa fa-eye text-warning" aria-hidden="true"></i></span></div>';
            $pkdF = '<div class="doc-stats" style="display: none;"><span class="doc" data-type="PKD" data-id="'.$value->shipment_num.'">'.(isset($status_arr["PKD"]["approved2"]) ? $status_arr["PKD"]["approved2"] : 0).'<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>'.(isset($status_arr["PKD"]["pending2"]) ? $status_arr["PKD"]["pending2"] : 0).'<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i> 0<i class="fa fa-eye text-warning" aria-hidden="true"></i></span></div>';
            $allF = '<div class="doc-stats" style="display: none;"><span class="doc"  data-id="'.$value->shipment_num.'">'.(isset($status_arr["all"]["approved"]) ? $status_arr["all"]["approved"] : 0).'<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>'.(isset($status_arr["all"]["pending"]) ? $status_arr["all"]["pending"] : 0).'<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i> 0<i class="fa fa-eye text-warning" aria-hidden="true"></i></span></div>';
            
            $hbl = '<span class="doc badge '.(isset($status_arr["HBL"]["color"])?$status_arr["HBL"]["color"]:"badge-danger").'" data-type="HBL" data-id="'.$value->shipment_num.'">'.(isset($status_arr["HBL"]["text"])?$status_arr["HBL"]["text"]:"missing").'</span>';
            $civ = '<span class="doc badge '.(isset($status_arr["CIV"]["color"])?$status_arr["CIV"]["color"]:"badge-danger").'" data-type="CIV" data-id="'.$value->shipment_num.'">'.(isset($status_arr["CIV"]["text"])?$status_arr["CIV"]["text"]:"missing").'</span>';
            $pkl = '<span class="doc badge '.(isset($status_arr["PKL"]["color"])?$status_arr["PKL"]["color"]:"badge-danger").'" data-type="PKL" data-id="'.$value->shipment_num.'">'.(isset($status_arr["PKL"]["text"])?$status_arr["PKL"]["text"]:"missing").'</span>';
            $pkd = '<span class="doc badge '.(isset($status_arr["PKD"]["color"])?$status_arr["PKD"]["color"]:"badge-danger").'" data-type="PKD" data-id="'.$value->shipment_num.'">'.(isset($status_arr["PKD"]["text"])?$status_arr["PKD"]["text"]:"missing").'</span>';
            $all = '<span class="doc badge '.(isset($status_arr["All"]["color"])?$status_arr["All"]["color"]:"badge-danger").'" data-id="'.$value->shipment_num.'">'.(isset($status_arr["All"]["text"])?$status_arr["All"]["text"]:"missing").'</span>';
              
            if(isset($status_arr) && $status_arr['HBL']['count'] > 0){
                $hbl.= '<span class="badge badge-danger navbar-badge ship-badge">'.$status_arr['HBL']['count'].'</span>';
            }
            if(isset($status_arr) && $status_arr['CIV']['count'] > 0){
                $civ.= '<span class="badge badge-danger navbar-badge ship-badge">'.$status_arr['CIV']['count'].'</span>';
            }  
            if(isset($status_arr) && $status_arr['PKL']['count'] > 0){
                $pkl.= '<span class="badge badge-danger navbar-badge ship-badge">'.$status_arr['PKL']['count'].'</span>';
            } 
            if(isset($status_arr) && $status_arr['PKD']['count'] > 0){
                $pkd.= '<span class="badge badge-danger navbar-badge ship-badge">'.$status_arr['PKD']['count'].'</span>';
            } 
            if(isset($status_arr) && $status_arr['All']['count'] > 0){
                $all.= '<span class="badge badge-danger navbar-badge ship-badge">'.$status_arr['All']['count'].'</span>';
            }

            $subdata =array();
            $subdata[] = $value->shipment_num;
            $subdata[] = $value->console_id;
            $subdata[] = date_format(date_create($value->eta), "m/d/Y H:i:s");;
            $subdata[] = date_format(date_create($value->etd), "m/d/Y H:i:s");;
            $subdata[] =  $hblF.'<div class="doc-stats">'.$hbl.'</div>';
            $subdata[] = $civF.'<div class="doc-stats">'.$civ.'</div>';
            $subdata[] = $pklF.'<div class="doc-stats">'.$pkl.'</div>';
            $subdata[] = $pkdF.'<div class="doc-stats">'.$pkd.'</div>';
            $subdata[] = $allF.'<div class="doc-stats">'.$all.'</div>';
            $subdata[] = 'No Comment';
            $data[] = $subdata;
        }
        
        $json_data=array(
            "data"              =>  $data,
        );

        echo json_encode($json_data);
    }

}
