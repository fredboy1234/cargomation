<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

/**
 * Docdeveloper Controller:
 *
 * @author John Alex
 * @since 1.0.8
 */

class Docdeveloper extends Core\Controller {

    /**
     * Docdeveloper Index: Renders the developer view. NOTE: This controller can only be accessed
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

        // Get an instance of the user role
        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user);

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        // $User->putUserLog([
        //     "user_id" => $user,
        //     "ip_address" => $User->getIPAddress(),
        //     "log_type" => 3,
        //     "log_action" => "Access doctracker",
        //     "start_date" => date("Y-m-d H:i:s"),
        // ]);

        $Shipment = Model\Shipment::getInstance();
        $Document = new Model\Document;
        
        if($role->role_id > 2) {
            $sub_account = $User->getSubAccountInfo($user);
            $user_key = $sub_account[0]->account_id;
        } else {
            $user_key = $user;
        }

        $shipment_id = $Shipment->getShipment($user_key, "shipment_num");
        // $docsCollection =array();
        // foreach($Document->getDocumentByShipment($shipment_id) as $key=>$value){
        //     $docsCollection[$value->shipment_num][$value->type][$value->status][] = $value;
        // }

        $document = $Document->getDocumentByShipment($shipment_id, "OTHER");

        $selectedTheme = $User->getUserSettings($user);
        
        if(isset( $selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = 'default';
        }

        $this->View->addCSS("css/shipment.css");
        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        //$this->View->addCSS("css/".$selectedTheme.".css");
        $this->View->addJS("js/developer.js");

        $imageList = (Object) Model\User::getProfile($user);
        $profileImage = '/img/default-profile.png';
        $emailList = $Shipment->getShipmentThatHasUser($user);
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }

        $this->View->renderTemplate("/docdeveloper/index", [
            "title" => "Document Developer",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user),
            "notifications" => Model\User::getUserNotifications($user),
            "menu" => Model\User::getUserMenu($role->role_id),
            "image_profile" => $profileImage,
            'role' => $role,
            'selected_theme' => $selectedTheme,
            // "shipment" => $Shipment->getShipment($user),
            "document" => $Document->getDocumentByShipment($shipment_id, "OTHER"),
            // "document_per_type" => $docsCollection,
            "child_user" => Model\User::getUsersInstance($user, $role->role_id),
            "user_settings" =>$User->defaultSettings($user_key, $role->role_id),
            "settings_user" => $User->getUserSettings($user),
            "client_user_shipments" => $Shipment->getClientUserShipment($user),
            'shipment_from_contact'=> $Shipment->getShipmentThatHasUser($user)
        ]);
    }

    public function view($doc_id = "", $user_id = "") {
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

        $Document = new Model\Document;
        $User = Model\User::getInstance($user_id);

        // USER LOGS!
        // $User->putUserLog([
        //     "user_id" => $user_id,
        //     "ip_address" => $User->getIPAddress(),
        //     "log_type" => 3,
        //     "log_action" => "Access doctracker",
        //     "start_date" => date("Y-m-d H:i:s"),
        // ]);

        // get client admin email
        if(!empty($User->getSubAccountInfo($user_id))) {
            $sub_account = $User->getSubAccountInfo($user_id);
            // "user email" change to "client email"
            $email = $sub_account[0]->client_email;
            $user_key = $sub_account[0]->account_id;
        } else {
            $email = $User->data()->email;
            $user_key = $User->data()->id;
        }

        // All doc type by client user
        $doc_type = $Document->getDocumentTypeByUser($user_key);

        // File API request
        $file = json_decode(file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/api/get/document/did/'.$doc_id.'/name,shipment_num,type'));
    
        // URL: https://cargomation.com/filemanager/cto@mail.com/CW_FILE/S00001055/MSC/Coversheet%20-%20S00001055.pdf
        $file_path = "E:/A2BFREIGHT_MANAGER/".$email."/CW_FILE/".$file[0]->shipment_num."/".$file[0]->type."/" . $file[0]->name;

        #$file_stat = $Document->checkDocumentType($file[0]->name, $file_path);
        $file_stat = $Document->getDocumentRank($doc_id)[0]->result;

        $this->View->renderWithoutHeaderAndFooter("/docdeveloper/view", [
            "title" => "Developer Viewer",
            "doc_id" => $doc_id,
            "user_id" => $user_id,
            "file_stat" => json_decode($file_stat),
            "doc_type" => $doc_type,
        ]);
    }

    public function learn($doc_id = "", $user_id = "") {
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

        $Document = new Model\Document;
        $User = Model\User::getInstance($user_id);

        // USER LOGS!
        // $User->putUserLog([
        //     "user_id" => $user_id,
        //     "ip_address" => $User->getIPAddress(),
        //     "log_type" => 3,
        //     "log_action" => "Access doctracker",
        //     "start_date" => date("Y-m-d H:i:s"),
        // ]);

        // get client admin email
        if(!empty($User->getSubAccountInfo($user_id))) {
            $sub_account = $User->getSubAccountInfo($user_id);
            // "user email" change to "client email"
            $email = $sub_account[0]->client_email;
            $user_key = $sub_account[0]->account_id;
        } else {
            $email = $User->data()->email;
            $user_key = $User->data()->id;
        }

        // File API request
        $file = json_decode(file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/api/get/document/did/'.$doc_id.'/name,shipment_num,type'));
    
        // OLD PATH
        // URL: https://cargomation.com/filemanager/cto@mail.com/CW_FILE/S00001055/MSC/Coversheet%20-%20S00001055.pdf
        $file_old_path = "E:/A2BFREIGHT_MANAGER/".$email."/CW_FILE/".$file[0]->shipment_num."/".$file[0]->type."/" . $file[0]->name;

        if(isset($_POST['type'])) {
            $new_type = strtolower(rtrim($_POST['type']));
        } else {
            echo "Invalid Request";
            exit;
        }

        // LEARN FILE
        $result = $Document->learnDocumentType($file[0]->name, $file_old_path, $new_type);

        if(!is_null($result->files[0]->type)) {
            $doc_type = strtoupper($result->files[0]->type);
            // NEW PATH
            $file_new_path = "E:/A2BFREIGHT_MANAGER/".$email."/CW_FILE/".$file[0]->shipment_num."/".$doc_type."/" . $file[0]->name;
            // MOVE FILE TO NEW PATH
            rename($file_old_path, $file_new_path);

            $data['doc_type'] = $doc_type;
            $data['doc_id'] = $doc_id;

            // update database
            $Document->updateDocumentType($data);
            $json_encode = json_encode($result);
            $Document->updateDocumentRank($doc_id, $json_encode);
        }

        // $response['status_code_header'] = 'HTTP/1.1 200 OK';
        // $response['body'] = json_encode($result);
        echo json_encode($result);;

    }
}