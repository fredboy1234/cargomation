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

class Invoice extends Core\Controller {

    /**
     * Invoice Index: Renders the invoice view. NOTE: This controller can only be accessed
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

        if($role->role_id > 2) {
            $sub_account = $User->getSubAccountInfo($user);
            $user_key = $sub_account[0]->account_id;
        } else {
            $user_key = $user;
        }

        $selectedTheme = $User->getUserSettings($user);
        
        if(isset( $selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = 'default';
        }

        $this->View->addCSS("css/invoice.css");
        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        //$this->View->addCSS("css/".$selectedTheme.".css");
        $this->View->addJS("js/invoice.js");

        $imageList = (Object) Model\User::getProfile($user);
        $profileImage = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }

        $this->View->renderTemplate("/invoice/index", [
            "title" => "AP Invoice (MOCK UP)",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user),
            "notifications" => Model\User::getUserNotifications($user),
            "menu" => Model\User::getUserMenu($role->role_id),
            "image_profile" => $profileImage,
            'role' => $role, 
            'user_id' => $user,
            'selected_theme' => $selectedTheme,
            "user_settings" =>$User->defaultSettings($user_key, $role->role_id),
            "settings_user" => $User->getUserSettings($user),
        ]);
    }

    public function processInvoice() {
        // Processing request.. 
        switch (strtoupper($this->requestMethod)) {
            case 'POST': 
                switch ($this->key) {
                    case 'upload':
                        if(isset($this->value) && !empty($this->value)) 
                            $response = $this->uploadDocument($this->param);
                        else 
                            $response = $this->unauthorizedAccess();
                        break;
                    case 'uploadchunk':
                        $response = $this->uploadChunk();
                        break;
                    case 'delete':
                        $response = $this->deleteDocument();
                    default:
                        # code...
                        break;
                }
                break;
            case 'GET':
                switch ($this->key) {
                    case 'sid': 
                        $response = $this->getDocumentByShipID($this->value, $this->param);
                        break;
                    case 'did':
                        $response = $this->getDocumentByDocID($this->value, $this->param);
                        break;
                    case 'uid':
                        $response = $this->getDocumentByUserID($this->value, $this->param);
                        break;
                    default:
                        $response = $this->unprocessableEntityResponse();
                        break;
                }
                break;
            case 'PUT':
                // $response = $this->updateDocumentFromRequest($this->userId);
                // break;
            case 'DELETE':
                // $response = $this->deleteDocument($this->userId);
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

    /**
     * Upload: Upload the bootstrap-fileinput files
     * returns associative array
     * @access private
     * 
     * @return array
     * @since 1.0.8
     */
    public function uploadDocument($param) {

        $User = Model\User::getInstance($param);
        // $shipment_num = $param[6];
        // $type = $param[7];
        $domain = "https://cargomation.com";
        $physical_path = "E:/A2BFREIGHT_MANAGER";

        $email = $User->data()->email;
        $user_id = $User->data()->id;

        // get client admin email
        if(!empty($User->getSubAccountInfo($user_id))) {
            $sub_account = $User->getSubAccountInfo($user_id);
            // "user email" change to "client email"
            $email = $sub_account[0]->client_email;
        }

        $preview = $config = $errors = [];
        $input = 'invoice'; // the input name for the fileinput plugin
        if (empty($_FILES[$input])) {
            return [];
        }
        $total = count($_FILES[$input]['name']); // multiple files
        // $path = './uploads/'; // your upload path
        $path = $physical_path . '/' . $email . '/CW_INVOICE/IN';

        for ($i = 0; $i < $total; $i++) {
            $tmpFilePath = $_FILES[$input]['tmp_name'][$i]; // the temp file path
            $fileName = $_FILES[$input]['name'][$i]; // the file name
            $fileSize = $_FILES[$input]['size'][$i]; // the file size
            $fileType = $_FILES[$input]['type'][$i]; 
            $fileExtn = pathinfo($fileName, PATHINFO_EXTENSION);

            //Make sure we have a file path
            if ($tmpFilePath != ""){

                //Setup our new file path
                $newFilePath = $path . "/" . $fileName;
                $newFileUrl = $domain . "/filemanager/" . $email . "/CW_INVOICE/IN/" . $fileName;

                // On the other hand, 'is_dir' is a bit faster than 'file_exists'.
                if (!is_dir($path . "/")) {
                    // @mkdir($path);
                    mkdir($path . "/", 0777, true);
                }

                //Upload the file into the new path
                if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $fileId = $fileName . $i; // some unique key to identify the file
                    $preview[] = $newFileUrl;
                    $config[] = [
                        'key' => $fileId,
                        'caption' => $fileName,
                        'size' => $fileSize,
                        'downloadUrl' => $newFileUrl, // the url to download the file
                        'url' => '/delete.php', // server api to delete the file based on key
                        'type' => $fileExtn
                    ];
                    
                    // save to database
                    // $result = $this->putInvoice($shipment_num, $type, $fileName);

                    // if requestToken cookie is exist
                    // if(Utility\Cookie::exists("requestToken")) {
                    //     $Document = new Model\Document();
                    //     $requestToken = Utility\Cookie::get("requestToken");
                    //     $Document->putRequestedStatus("done", $requestToken);
                    //     Utility\Cookie::delete("requestToken");
                    // }
                } else {
                    $errors[] = $fileName;
                }
            } else {
                $errors[] = $fileName;
            }
        }

        // push to cargowise
        // User setting (tick box) 
        // $user_settings = $User->getUserSettings($param[5]);
        // $document_settings = json_decode($user_settings[0]->document);
        // if(isset($document_settings->doctracker->auto_push)) {
        //     self::uploadToCargoWise($shipment_num, $email, $config);
        // }
        $out = ['initialPreview' => $preview, 'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];
        if (!empty($errors)) {
            $img = count($errors) === 1 ? 'file "' . $error[0]  . '" ' : 'files: "' . implode('", "', $errors) . '" ';
            $out['error'] = 'Oh snap! We could not upload the ' . $img . 'now. Please try again later.';
        }
        // return $out;

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($out);
        return $response;
    }


}