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

        Utility\Redirect::to('/dashboard');

        // if(Model\Role::isAdmin($User)) {
            
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
            // $this->View->renderTemplate("admin", "admin/dashboard", [
            //     "title" => "Dashboard",
            //     "data" => (new Presenter\Profile($User->data()))->present(),
            //     "users" => Model\User::getUsersInstance($userID)
            // ]);
        // } else {
        //     Utility\Redirect::to(APP_URL);
        // }

        // $this->View->renderTemplateTwig("/admin/index.php", [
        //     "title" => "Admin"
        // ]);
    }

    public function show($user_id = "") {
        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        $User = new Model\User;
        $user_info = $User->getUserInfoByUserID($user_id)[0];

        $this->View->renderWithoutHeaderAndFooter("admin/user/show", [
            "title" => "Contact Info",
            "user_id" => $user_id,
            "user_info" => $user_info,
            "total_shipment" => 1,
            "document_stats" => 1,
            "document_type" => 1
        ]);

    }

    public function edit($user_id = "") {
        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        $User = new Model\User;

        $this->View->renderWithoutHeaderAndFooter("admin/user/edit", [
            "title" => "User Info",
            "user_id" => $user_id,
            "user_info" => $User->getUserInfoByUserID($user_id)[0],
            "document_type" => $User->getCWDOcumentType($user_id)
        ]);

    }

    public function update($user_id = "", $form_type = "") {
        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();
        $User = new Model\User;

        switch ($form_type) {
            case 'information':
                $has_error = $User->updateUsers($_POST, $user_id);
                break;
            case 'shipment':
                # code...
                break;
            case 'document':
                $this->updateFilterSettings($_POST);
                $has_error = $User->updateCWDocumentType($_POST, $user_id);
                // $has_error = $User->updateUserInfo($_POST, $user_id);
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
        // $Contact = new Model\Contact;
        // $Contact->deleteContactInfo($contact_id);
    }

    public function updateFilterSettings($data) {
        $User = new Model\User;
        $userData = $User->getUserSettings($data['user_id']);
        $userData = !isset($userData)?json_decode($userData[0]->shipment):array();
        // ALL DOCUMENT IN USER CW
        $all_document = $User->getCWDOcumentType($data['user_id']);
        // Default Shipment Filter Setting
        $shipment_setting = '/settings/sub-shipment-settings.json';
        $defaultSettings = json_decode(file_get_contents(PUBLIC_ROOT.$shipment_setting));
        $defaultCollection = array();
        if(isset($userData) && !empty($userData)){
            foreach($userData as $key => $value){
                $defaultCollection[]=$value->index_value;
            }
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
        if(!empty($data['doc_type'])){
            $count = 11;
            foreach ($all_document as $key => $value) {
                if(in_array($value->doc_type, $data['doc_type'])) {
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
            }
        } 

        // if(!empty($User->getUserSettings($user)[0]->shipment)){
        //     return $User->getUserSettings($user)[0]->shipment;
        // }else{
        //     return json_encode($userData);    
        // }
        return $User->updateUserSettings2('shipment', $userData, $_POST['user_id']);
    }

    /* NEED TO REVIEW CODE BELOW */

    // public function isAuthorized($User){
    //     $id = $User->data()->role;
    //     $role = Model\Role::getRole($id);

    //     if(isset($role) && $role == 'admin'){
    //         return true;
    //     }
    //     return false;
    // }

}