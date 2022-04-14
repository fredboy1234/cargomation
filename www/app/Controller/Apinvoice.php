<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;


class Apinvoice extends Core\Controller {

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
        $this->View->addJS("js/apinvoice.js");

        $imageList = (Object) Model\User::getProfile($user);
        $profileImage = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }
        $headerMatched = array();
        $headerParsed = array();
        $invoicesHeader = array();

        $data['apinvoice'] = json_decode($this->geTempData()[0]->match_report);
        $data['invoices'] = $this->getInvoices();
        $matched =  $data['apinvoice']->HubJSONOutput->CargoWiseMatchedData;
        $parsed = $data['apinvoice']->HubJSONOutput->ParsedPDFData;

        // echo"<pre>";
        // print_r( $data['invoices']);
        // exit();
        $notin = array("sec_ref","id");
        $invoicesHeader =array(
            "Process ID",
            "File Name",
            "Job Number",
            "Date Uploaded",
            "Uploaded By",
            "Action",
            "Status");
        // foreach($data['invoices'] as $val){
        //     foreach($val as $key=>$cval){
        //         if(!in_array($key,$invoicesHeader) && !in_array($key,$notin)){
        //             $invoicesHeader[] = $key;
        //         }
        //     }
                
        // }
        
        //echo "<pre>";
        foreach($matched->CWChargeLines->ChargeLine as $key=>$m){
            foreach($m as $mkey=>$mval){
                if(!in_array($mkey,$headerMatched)){
                    $headerMatched[] = $mkey;
                }
                 
            }   
        }

        foreach($parsed->ParsedPDFChargeLines->ChargeLine as $key=>$m){
            foreach($m as $mkey=>$mval){
                if(!in_array($mkey,$headerParsed)){
                    $headerParsed[] = $mkey;
                }  
            }  
        }
    //    echo"<pre>";
    //     print_r($data['invoices']);
    //     exit();
        $this->View->renderTemplate("/apinvoice/index", [
            "title" => "Upload AP Invoice",
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
            "apinvoice" =>  $data['apinvoice'],
            "headerMatched"=>$headerMatched,
            "headerParsed" =>$headerParsed,
            "parsedData" => json_encode($parsed),
            "invoicesHeader" =>$invoicesHeader,
        ]);
    }

    public function invoicesData(){
        $retData = array();
        $data['invoices'] = $this->getInvoices();
        foreach($data['invoices'] as $value){
            $retData['data'][] = array(
                "Process ID" => $value->process_id,
                "File Name" => $value->filename,
                "Job Number" => "empty",
                "Date Uploaded"=> date("d/m/Y"),
                "Uploaded By" => $value->uploadedby,
                "Action"=> "<div class='container'><div class='row'><div class='col-xs-6'></div><div class='col-xs-6'><button type='button' class='btn btn-block btn-outline-danger'>Delete</button></div></div></div>",
                "Status"=> "Processing",
            );
        }
        echo json_encode($retData);
    }

    public function invoiceSuccess(){
        $retData = array();
        $data['invoices'] = $this->getInvoicesSuccess();
        // echo"<pre>";
        // print_r($data['invoices']);
        // exit();
        foreach($data['invoices'] as $value){
            $retData['data'][] = array(
                "Process ID" => $value->prim_ref,
                "File Name" => $value->filename,
                "Job Number" => $value->sec_ref,
                "Date Uploaded"=> date("d/m/Y"),
                "Uploaded By" => "not added to table",
                "Action"=> "<div class='container'><div class='row'><div class='col-xs-6'></div><div class='col-xs-6'><button type='button' class='btn btn-block btn-outline-danger'>Delete</button></div></div></div>",
                "Status"=> $value->status,
            );
        }
        echo json_encode($retData);
    }

    public function headerData(){
        $header=array();
        $columnMatched = array();
        $data['apinvoice'] = json_decode($this->geTempData()[0]->match_report);
        $matched =  $data['apinvoice']->HubJSONOutput->CargoWiseMatchedData;
        
        foreach($matched->CWChargeLines->ChargeLine as $key=>$m){
            foreach($m as $mkey=>$mval){
                if(!in_array($mkey,$columnMatched)){
                    $columnMatched[] = array("data"=>$mkey);
                }  
            }  
        }
        $header['data'] =  $matched->CWChargeLines->ChargeLine;
       
       // $header['columns'] =  $columnMatched;      
    //    $header = array(
    //         "draw"            => 1,   
    //         "recordsTotal"    => 2,  
    //         "recordsFiltered" => 2,
    //         "data"            => $matched->CWChargeLines->ChargeLine,
    //     );
        echo json_encode($header);
    }

    public function parsedData(){
        $parsed = array();
        $data['apinvoice'] = json_decode($this->geTempData()[0]->match_report);
        $columnMatched = array();
        $data['apinvoice']->HubJSONOutput->ParsedPDFData;
        
        $parsed['data'] = $data['apinvoice']->HubJSONOutput->ParsedPDFData->ParsedPDFChargeLines->ChargeLine;
        echo json_encode($parsed);
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

    public function validateToken($token) {
        if(isset($token)){
            return true;
        }
        return false;
    }

    public function customUpload(){
        if($_FILES['file']['name'] != ''){
            $test = explode('.', $_FILES['file']['name']);
            $extension = end($test);    
            $name = $_FILES['file']['name'];
            
            $User = Model\User::getInstance($_SESSION['user']);
            $email = $User->data()->email;
           // $user_id = $User->data()->id;
            
            $newFilePath = "E:/A2BFREIGHT_MANAGER/hub@tcfinternational.com.au/CW_APINVOICE/IN/";
            //$newFileUrl = "https://cargomation.com/filemanager/" . $email . "/CW_INVOICE/IN/";

            $location = $newFilePath.$name;
           move_uploaded_file($_FILES['file']['tmp_name'], $location);
            
           // $file_server_path = realpath($newFileUrl.$name);
            $data['user_id'] = $_SESSION['user'];
            $data['filename'] = $name;
            $data['filepath'] = 'https://cargomation.com/filemanager/hub@tcfinternational.com.au/CW_APINVOICE/IN/'.$name;
            $data['uploadedby']= $email;
            $APinvoice = Model\Apinvoice::getInstance();
            
            $APinvoice->insertMatchHeader($data);

            //print_r($file_server_path);
            $arr  = array(
                'file'=> 'https://cargomation.com/filemanager/hub@tcfinternational.com.au/CW_APINVOICE/IN/'.$name,
                'client' => 'A2B',
                'user_id' => $_SESSION['user']
            );
           
           // $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
           
            $url ='https://cargomation.com:8001/compare'; 
            
            $result = $this->post($url, $arr, '');

           print_r($result);
           return "success man bai";
        }
    }

    
    private function post($url, $payload, $headers) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    
    }

    // main upload function used above
    // upload the bootstrap-fileinput files
    // returns associative array
    public function upload() {
        print_r($_POST);
        exit();
        $User = Model\User::getInstance($_POST['user_id']);
        $email = $User->data()->email;
        $user_id = $User->data()->id;
        // get client admin email
        if(!empty($User->getSubAccountInfo($user_id))) {
            $sub_account = $User->getSubAccountInfo($user_id);
            // "user email" change to "client email"
            $email = $sub_account[0]->client_email;
        }
        //Setup our new file path
        $newFilePath = "E:/A2BFREIGHT_MANAGER/" . $email . "/AP_INVOICE/IN/";
        $newFileUrl = "https://cargomation.com/filemanager/" . $email . "/CW_INVOICE/IN/";

        $preview = $config = $errors = [];
        $targetDir = $newFilePath; // uploads
        // On the other hand, 'is_dir' is a bit faster than 'file_exists'.
        if (!is_dir($newFilePath)) {
            // @mkdir($path);
            mkdir($newFilePath, 0777, true);
        }
        // if (!file_exists($targetDir)) {
        //     @mkdir($targetDir);
        // }

        $fileBlob = 'fileBlob';                         // the parameter name that stores the file blob
        if (isset($_FILES[$fileBlob]) && isset($_POST['uploadToken'])) {
            // $token = $_POST['uploadToken'];          // gets the upload token
            // if ($validateToken($token)) {            // your access validation routine (not included)
            //     return [
            //         'error' => 'Access not allowed'  // return access control error
            //     ];
            // }
            $file = $_FILES[$fileBlob]['tmp_name'];  // the path for the uploaded file chunk 
            $fileName = $_POST['fileName'];          // you receive the file name as a separate post data
            $fileSize = $_POST['fileSize'];          // you receive the file size as a separate post data
            $fileId = $_POST['fileId'];              // you receive the file identifier as a separate post data
            $index =  $_POST['chunkIndex'];          // the current file chunk index
            $totalChunks = $_POST['chunkCount'];     // the total number of chunks for this file
            $targetFile = $targetDir.'/'.$fileName;  // your target file path
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            if ($totalChunks > 1) {                  // create chunk files only if chunks are greater than 1
                $targetFile .= '_' . str_pad($index, 4, '0', STR_PAD_LEFT); 
            } 
            if ($ext == 'pdf') {
                $ext = 'pdf';
            } else if ($ext == 'txt') {
                $ext = 'text';
            } else if ($ext == 'tif' || $ext == 'ai' || $ext == 'tiff' || $ext == 'eps') {
                $ext = 'gdocs';  
            } else if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                $ext = 'image'; 
            } else {
                $ext = 'office';
            }
            $thumbnail = 'unknown.jpg';
            if(move_uploaded_file($file, $targetFile)) {
                // get list of all chunks uploaded so far to server
                $chunks = glob("{$targetDir}/{$fileName}_*"); 
                // check uploaded chunks so far (do not combine files if only one chunk received)
                $allChunksUploaded = $totalChunks > 1 && count($chunks) == $totalChunks;
                if ($allChunksUploaded) {           // all chunks were uploaded
                    $outFile = $targetDir.'/'.$fileName;
                    // combines all file chunks to one file
                    combineChunks($chunks, $outFile);
                } 
                // if you wish to generate a thumbnail image for the file
                // $targetUrl = getThumbnailUrl($path, $fileName);
                $targetUrl = $newFileUrl;
                // separate link for the full blown image file
                $zoomUrl = $newFileUrl . $fileName;
                $out = [
                    'chunkIndex' => $index,         // the chunk index processed
                    'initialPreview' => $newFileUrl . $fileName, // the thumbnail preview data (e.g. image)
                    'initialPreviewConfig' => [
                        [
                            'type' => $ext,      // check previewTypes (set it to 'other' if you want no content preview)
                            'caption' => $fileName, // caption
                            'key' => $fileId,       // keys for deleting/reorganizing preview
                            'fileId' => $fileId,    // file identifier
                            'size' => $fileSize,    // file size
                            'zoomData' => $zoomUrl, // separate larger zoom data
                        ]
                    ],
                    'append' => true
                ];
                echo json_encode($out);
            } else {
                return [
                    'error' => 'Error uploading chunk ' . $_POST['chunkIndex']
                ];
            }
        }
        return [
            'error' => 'No file found'
        ];
    }
    
    // combine all chunks
    // no exception handling included here - you may wish to incorporate that
    public function combineChunks($chunks, $targetFile) {
        // open target file handle
        $handle = fopen($targetFile, 'a+');
        
        foreach ($chunks as $file) {
            fwrite($handle, file_get_contents($file));
        }
        
        // you may need to do some checks to see if file 
        // is matching the original (e.g. by comparing file size)
        
        // after all are done delete the chunks
        foreach ($chunks as $file) {
            @unlink($file);
        }
        
        // close the file handle
        fclose($handle);
    }
    
    // generate and fetch thumbnail for the file
    public function getThumbnailUrl($path, $fileName) {
        // assuming this is an image file or video file
        // generate a compressed smaller version of the file
        // here and return the status
        $sourceFile = $path . '/' . $fileName;
        $targetFile = $path . '/thumbs/' . $fileName;
        //
        // generateThumbnail: method to generate thumbnail (not included)
        // using $sourceFile and $targetFile
        //
        if (generateThumbnail($sourceFile, $targetFile) === true) { 
            return '/uploads/thumbs/' . $fileName;
        } else {
            return '/uploads/' . $fileName; // return the original file
        }
    }

    public function edit(){
        $data = array();
        if(isset($_POST)){
          $data =  $_POST['data']['ParsedPDFChargeLines']['ChargeLine'][$_POST['index']];
        }
        $this->View->addJS("js/apinvoice.js");
       
        $this->View->renderWithoutHeaderAndFooter("/apinvoice/edit", [
            "data" => $data,
            "apinvoice"=>$_POST['apinvoice'],
            "index"=>$_POST['index'],
         ]);
    }

    public function sendToAPI(){
        $toPass = array();
        $APinvoice = Model\Apinvoice::getInstance();
        if(isset($_POST)){
            foreach($_POST['data'] as $key=>$val){
                foreach($val as $vkey=>$vval){
                    $toPass[$vkey]=$vval;
                }
            }
            
            $_POST['apinvoice']['HubJSONOutput']['ParsedPDFData']['ParsedPDFChargeLines']['ChargeLine'][$_POST['index']] = $toPass;
           $APinvoice->addToCGM_Response(json_encode($_POST['apinvoice']));
        }
    }

    public function geTempData(){
        $APinvoice = Model\Apinvoice::getInstance();
        return $APinvoice->getMatchReport(2);
    }

    public function getInvoices(){
        $APinvoice = Model\Apinvoice::getInstance();
        return $APinvoice->getInvoices();
    }

    public function getInvoicesSuccess(){
        $APinvoice = Model\Apinvoice::getInstance();
        return $APinvoice->getInvoicesSuccess();
    }


}