<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

/**
 * Register Controller:
 *
 * @author John Alex
 * @since 1.0.2
 */
class Contact extends Core\Controller {

    /**
     * Index: Renders the register view. NOTE: This controller can only be
     * accessed by unauthenticated users!
     * @access public
     * @example register/index
     * @return void
     * @since 1.0.2
     */
    public function index() {

        // Check that the user is unauthenticated.
        Utility\Auth::checkAuthenticated();

        // Get an instance of the user model using the ID stored in the session. 
        $userID = Utility\Session::get(Utility\Config::get("SESSION_USER"));
        if (!$User = Model\User::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }        

        if (!$Role = Model\Role::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($userID);

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }
        
        $selectedTheme = $User->getUserSettings($userID);
        if(isset($selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = '';
        }

        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        $this->View->addCSS("css/".$selectedTheme.".css");
       
        // Render view template
        // Usage renderTemplate(string|$template, string|$filepath, array|$data)

        $imageList = (Object) Model\User::getProfile($userID);
        $profileImage = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }

        // Set any dependencies, data and render the view.
        $this->View->addJS("js/contact.js");

        // Render view template
        // Usage renderTemplate(string|$template, string|$filepath, array|$data)
        $this->View->renderTemplate("contact/index", [
            "title" => "Contact Us",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "notifications" => Model\User::getUserNotifications($userID),
            "menu" => Model\User::getUserMenu($userID, $role->role_id),
            "user" => (Object) Model\User::getProfile($userID),
            "users" => Model\User::getUsersInstance($userID, $role->role_id),
            "image_profile" => $profileImage,
            "selected_theme" => $selectedTheme,
            "role" => $role
        ]);
    }

    public function sendEmail($userID=""){
        
        $userID = Utility\Session::get(Utility\Config::get("SESSION_USER"));
        if (!$User = Model\User::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }  
        
        if(isset($_POST)){
            $mail = Model\SendMail::sendContactus($_POST);
            // return json_encode(["success"=>true]);
            echo json_encode($mail);
        }
      
    }

    public function sendEmailAPI($userID=""){
        
        $userID = Utility\Session::get(Utility\Config::get("SESSION_USER"));
        if (!$User = Model\User::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }  
        
        if(isset($_POST)){
            $mail = Model\SendMail::sendContactusAPI($_POST);
            // return json_encode(["success"=>true]);
            echo json_encode($mail);
        }
      
    }

    public function edit($contact_id = "") {
        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        $Contact = new Model\Contact;
        $User = new Model\User;

        $contact_info = $Contact->getContactInfo($contact_id)[0];

        $this->View->renderWithoutHeaderAndFooter("/contact/edit", [
            "title" => "Contact Info",
            "contact_id" => $contact_id,
            "contact_info" => $contact_info,
            "cw_document_type" => $User->getClientDocumentType($contact_info->account_id),
            "document_type" => $User->getSubDocumentType($contact_info->uid)

        ]);

    }

    public function show($contact_id = "") {
        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        $Contact = new Model\Contact;
        $contac_info = $Contact->getContactInfo($contact_id)[0];

        $Shipment = Model\Shipment::getInstance();
        
        // CUSTOMER
        $data['is_customer'] = true;
        $data['org_code'] = $contac_info->organization_code;

        $this->View->renderWithoutHeaderAndFooter("/contact/show", [
            "title" => "Contact Info",
            "contact_id" => $contact_id,
            "contact_info" => $contac_info,
            "total_shipment" => count($Shipment->getShipmentDynamic($contact_id, 'user_id', '', $data)),
            "document_stats" => Model\Document::getDocumentStats($contact_id, $contac_info->organization_code),
            "document_type" => Model\Document::getDocumentTypeByOrg($contac_info->organization_code)
        ]);

    }

    public function update($contact_id = "", $form_type) {
        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();
        $Contact = new Model\Contact;
        switch ($form_type) {
            case 'information':
                $has_error = $Contact->updateContactInfo($contact_id, $_POST);
                break;
            case 'shipment':
                # code...
                break;
            case 'document':
                $User = new Model\User;
                $settings = $this->sanitizeSettings($User, $contact_id, $_POST);
                $has_error = $User->updateUserSettings2('shipment', $settings, $contact_id);
                // $has_error = $User->updateCWDocumentType($_POST, $contact_id);
                break;
            case 'settings':
                # code...
                break;
            
            default:
                # code...
                break;
        }
        
        echo json_encode($has_error);
    }

    public function delete($contact_id = "") {
        $Contact = new Model\Contact;
        $Contact->deleteContactInfo($contact_id);
    }

    public function sanitizeSettings($User, $user_id, $data) {

        // DEFAULT SHIPMENT COLUMN SETTTING
        $json_setting = '/settings/sub-shipment-settings.json';
        $defaultSettings = json_decode(file_get_contents(PUBLIC_ROOT.$json_setting));

        $shipment_settings = [];

        // SHIPMENT COLUMN NEEDS TO SHOW
        $need_show = empty($data['data']) ? [1] : $data['data'];
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

        // DEFAULT DOCUMENT COLUMN SETTING (SUB ACCOUNT)
        $doc_type = $User->getCWDOcumentType($user_id, $data['account_id']);

        // DOCUMENT COLUMN NEEDS TO SHOW
        if(!empty($doc_type)){
            foreach ($doc_type as $key => $value) {
                if(in_array($value->doc_type, $need_show)){
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
        return $shipment_settings;
    }

}
