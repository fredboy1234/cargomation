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
        $data['invoices'] = array();
        $data['apinvoice'] = array();
        $parsed = array();

        if(isset($this->geTempData()[0])){
            $data['apinvoice'] = json_decode($this->geTempData()[0]->match_report);
            $data['invoices'] = $this->getInvoices($_SESSION['user']);
            $matched =  $data['apinvoice']->HubJSONOutput->CargoWiseMatchedData;
            $parsed = $data['apinvoice']->HubJSONOutput->ParsedPDFData;
            // echo"<pre>";
            // print_r( $data['invoices']);
            // exit();
            $notin = array("sec_ref","id");
            $invoicesHeader =array(
                "Process ID",
                "File Name",
                "Doc Number",
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
            if(isset($matched->CWChargeLines->ChargeLine)){
                foreach($matched->CWChargeLines->ChargeLine as $key=>$m){
                    foreach($m as $mkey=>$mval){
                        if(!in_array($mkey,$headerMatched)){
                            $headerMatched[] = $mkey;
                        }
                        
                    }   
                }
            }
            
            if(isset($parsed->ParsedPDFChargeLines->ChargeLine)){
                foreach($parsed->ParsedPDFChargeLines->ChargeLine as $key=>$m){
                    foreach($m as $mkey=>$mval){
                        if(!in_array($mkey,$headerParsed)){
                            $headerParsed[] = $mkey;
                        }  
                    }  
                }
            }
            
        }
       // $countque = $this->getSingleInvoice($_SESSION['user']);
       // $completeCount = $this->getAPCompleteCount($_SESSION['user']);
       // $quecount = $this->getAPQUECount($_SESSION['user']);
       // $archivecount = $this->getAPArchiveCount($_SESSION['user']);

        $this->View->renderTemplate("/apinvoice/index", [
            "title" => "Upload AP Invoice",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user),
            "notifications" => Model\User::getUserNotifications($user),
            "menu" => Model\User::getUserMenu($user, $role->role_id),
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
           // "allcount" =>is_countable($countque) ? sizeof($countque) : 0, 
           // "completeCount"=>$completeCount[0]->completed,
           // "quecount"=>$quecount[0]->que,
           // "archivecount"=>$archivecount[0]->archive,
            "chartData" =>$this->chartData($_SESSION['user'])
        ]);
    }

    public function invoicesData(){
        $retData = array();
        
        $data['invoices'] = $this->getInvoices($_SESSION['user']);
        // echo"<pre>";
        // print_r($data['invoices']);
        // exit();
        foreach($data['invoices'] as $value){
            $retData['data'][] = array(
                "Process ID" => $value->process_id,
                "File Name" => $value->filename,
                "Doc Number" => "empty",
                "Date Uploaded"=> date("d/m/Y"),
                "Uploaded By" => $value->uploadedby,
                "Action"=> "<div class='container'><div class='row'><div class='col-xs-6'></div><div class='col-xs-6'><button type='button' class='btn btn-block btn-outline-danger'>Delete</button></div></div></div>",
                "Status"=> "Processing",
                "invoices"=>''
            );
        }
        echo json_encode($retData);
    }

    public function invoiceSuccess(){
        $retData = array();
        $invoiceHeader = array();
        $splitInvoice = array();
        $data['invoices'] = $this->getSingleInvoice($_SESSION['user']);
        
        $retData['completedCount'] = $completeCount = $this->getAPCompleteCount($_SESSION['user']);
        $retData['que'] =  $this->getAPQUECount($_SESSION['user']);
        $retData['archive'] = $this->getAPArchiveCount($_SESSION['user']);
            
        if(isset($_POST['type'])){
            $data['invoices'] = $this->getSingleInvoiceBytype($_SESSION['user'],$_POST['type']);
        }
        foreach($data['invoices'] as $value){
            $splitInvoice[$value->process_id][] = $value;
        }
        //print_r($splitInvoice);
        foreach($splitInvoice as $key=>$v){
            $invoiceHeader['invoice'] = array();
           foreach($v as $value){
                $invoiceHeader['invoice_num'] = '';
                $invoiceHeader['invoice_report'] ='';
                $invoiceHeader['invoice_response'] ='';
                $invoiceHeader['invoice_status'] = '';
                if(!empty($value->match_report)){
                    $parsed = json_decode($value->match_report);
                    $parsedChargline = $parsed->HubJSONOutput->ParsedPDFData->ParsedPDFChargeLines;
                
                    if(isset($parsedChargline->ChargeLine) && !empty($parsedChargline->ChargeLine)){
                    
                        foreach($parsedChargline ->ChargeLine as $chline){
                            $invoiceHeader['invoice_num'] = $chline->InvoiceNumber ;
                            $invoiceHeader['invoice_report'] = $parsed->HubJSONOutput->MatchReport->Status;
                            $invoiceHeader['invoice_response'] = "ready";
                            $invoiceHeader['invoice_status'] = "complete";
                        }
                    }
                    $actionbtn = '';
                    if(!is_null($value->cw_response) || !empty($value->cw_response)){
                        $actionbtn = "<button type='button' class='btn btn-block btn-outline-success cwresmodal' data-pid='".$value->process_id."'>CW Response</button>";
                    }
                    
                    $invoiceHeader['invoice'][]  = "INVOICE_{$invoiceHeader['invoice_num']},
                                                        {$invoiceHeader['invoice_report']},
                                                        <span class='badge bg-danger'>{$invoiceHeader['invoice_response']}</span>,
                                                        <span class='badge bg-warning'>{$invoiceHeader['invoice_status']}</span>,
                                                        ".$actionbtn.",".$value->cw_response_status;
                } 
           
                $jobnum = json_decode($value->sec_ref);
                $actionbtn = "<div class='container'><div class='row'><div class='col-xs-6'></div><div class='col-xs-6'><button data-pd='{$value->process_id}' type='button' class='toarchive btn btn-block btn-outline-danger'>Archive</button></div></div></div>";
           
           }
            
            // if(!is_null($value->cw_response) || !empty($value->cw_response)){
            //     $actionbtn = "<div class='container cwresmodal'><div class='row'><div class='col-xs-6'></div><div class='col-xs-6'><button type='button' class='btn btn-block btn-outline-success' data-pid='".$value->process_id."'>CW Response</button></div></div></div>";
            // }
            
            $cstatus = '';
            if(!is_null($value->cw_response_status)){
                $cstatus = $value->cw_response_status ==='Success' ? 'Complete' : $cstatus = $value->cw_response_status;
            }else{
                if(is_null($value->status) || empty($value->status)){
                    $cstatus = 'Processing';
                }else{
                    $cstatus =  $value->status;
                }
            }
            
            $retData['data'][] = array(
                "Process ID" => $value->process_id,
                "File Name" => $value->maAPFIlename,
                "Doc Number" => (!isset($jobnum->doc_number) ? 'Empty' : (is_null($jobnum->doc_number) ? 'Empty ' : $jobnum->doc_number)) ,
                "Date Uploaded"=> date('d/m/y H:i a', strtotime($value->dateuploaded)),
                "Uploaded By" => $value->uploadedby,
                "Action"=> $actionbtn,
                "Status"=> $cstatus,
                "invoices" => $invoiceHeader['invoice'],
                "pid" =>$value->process_id
            );
            
        }
    //   print_r($retData['data']);
        //exit();
        echo json_encode($retData);
        $this->reprocessReportONSuccess();
    }

    public function headerData(){
        $header=array();
        $header['data'] = array();
        $columnMatched = array();
        
        if(isset($_POST['prim_ref'])){
            $data['apinvoice'] = json_decode($this->getMatchReportWidthID($_POST['prim_ref'])[0]->match_report);
        }else{
            $data['apinvoice'] = json_decode($this->geTempData()[0]->match_report);
        }
        if(!isset( $data['apinvoice']->HubJSONOutput->CargoWiseMatchedData)){
            exit;
        }
        $matched =  $data['apinvoice']->HubJSONOutput->CargoWiseMatchedData;
        if(isset($matched->CWChargeLines->ChargeLine)){
            foreach($matched->CWChargeLines->ChargeLine as $key=>$m){
                foreach($m as $mkey=>$mval){
                    if(!in_array($mkey,$columnMatched)){
                        $columnMatched[] = array("data"=>$mkey);
                    }  
                }  
            }
            $header['data'] =  $matched->CWChargeLines->ChargeLine;
        }
        
        echo json_encode($header);
    }

    public function parsedData(){
        $parsed = array();
    
        if(isset($_POST['prim_ref'])){
            $cgm = json_decode($this->getMatchReportWidthID($_POST['prim_ref'])[0]->cgm_response);
            if(is_null( $cgm ) || empty( $cgm)){
                $data['apinvoice'] = json_decode($this->getMatchReportWidthID($_POST['prim_ref'])[0]->match_report); 
            }else{
                $data['apinvoice'] = $cgm;
            }
             
        }else{
            $data['apinvoice'] = json_decode($this->geTempData()[0]->match_report);
        }
        
        $columnMatched = array();
        $data['apinvoice']->HubJSONOutput->ParsedPDFData;
        $pline =  $data['apinvoice']->HubJSONOutput->ParsedPDFData->ParsedPDFChargeLines->ChargeLine;

        if(!empty($pline)){
            foreach($pline as $pval){
                $container = '';
                $btncon ='';
                if(is_array($pval->Container)){
                    foreach($pval->Container as $pcontainer){
                        $btncon .= $pcontainer.'<br>';
                    }
                }
                $container = '<div class="btn-group show">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                         View
                    </button>
                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                    <span class="dropdown-item">
                    '.$btncon.'
                    </span>
                    </div>
                    </div>';
                $parsed['data'][] = array(
                    'ChargeCode' => $pval->ChargeCode,
                    'InvoiceNumber' => $pval->InvoiceNumber,
                    'InvoiceDate' => $pval->InvoiceDate,
                    'Container' => $container,
                    'ExchangeRate' => $pval->ExchangeRate,
                    'Creditor' => $pval->Creditor,
                    'InvoiceTo' => $pval->InvoiceTo,
                    'SubTotal' => $pval->SubTotal,
                    'GST' => $pval->GST,
                    'Discrepancy' => $pval->Discrepancy
                );
            }
        }
       
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
            
            $newFilePath = "E:/A2BFREIGHT_MANAGER/".$email."/CW_APINVOICE/IN/";
            //$newFileUrl = "https://cargomation.com/filemanager/" . $email . "/CW_INVOICE/IN/";

            if (!file_exists($newFilePath)) {
                mkdir($newFilePath, 0777, true);
            }
            
            $location = $newFilePath.$name;
           
            move_uploaded_file($_FILES['file']['tmp_name'], $location);
        
           // $file_server_path = realpath($newFileUrl.$name);
            // $data['user_id'] = $_SESSION['user'];
            // $data['filename'] = $name;
            // $data['filepath'] = 'https://cargomation.com/filemanager/hub@tcfinternational.com.au/CW_APINVOICE/IN/'.$name;
            // $data['uploadedby']= $email;
            // $APinvoice = Model\Apinvoice::getInstance();
            
            // $APinvoice->insertMatchHeader($data);

            //print_r($file_server_path);
            $arr  = array(
                'file'=> 'https://cargomation.com/filemanager/'.$email.'/CW_APINVOICE/IN/'.$name,
                'client' => 'A2B',
                'user_id' => $_SESSION['user'],
                'process_id' => $this->getLastID()
            );
           
           // $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
           
            $url ='https://cargomation.com:8001/compare'; 
            
            $result = $this->post($url, $arr, '');
            print_r($arr);
            print_r($result);
           return "success";
        }
    }

    public function reprocessMatchReport(){
       
        if(isset($_POST)){
            $user_id = $_SESSION['user'];
            $process_id =  $this->getMatchReportWidthID($_POST['prim_ref'])[0]->id;
            $arr = array(
                "user_id" => strval($user_id),
                "id"=>(int) $process_id
            );

            $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
            $headers = ["Authorization: Basic YWRtaW46dVx9TVs2enpBVUB3OFlMeA==",
                        "Content-Type: application/json"];
    
            //$url ='https://cargomation.com:5200/redis/apinvoice/compare'; 
            $url ='https://cargomation.com:5200/redis/apinvoice/match_report';
             $result = $this->postAuth($url,$payload,$headers);
            print_r($payload);
            print_r($result);
        }
    }

    public function reprocessReportONSuccess(){
            $user_id = $_SESSION['user'];
            $process_id = $this->getLastID();
            $parseinv = $this->getParseINV($user_id);
            
            //print_r($this->getParseINV($user_id));
           //exit;   
           if(!empty($parseinv)){
            foreach($parseinv as $parse){
                $decodeINV = json_decode($parse->parsed_inv);
               // print_r($decodeINV);
               
               if(!empty($decodeINV)){
                foreach($decodeINV as $inv){
                   
                    $arr = array(
                        "user_id" => strval($user_id),
                        "jsonstring"=>json_encode($inv)
                    );
        
                    $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
                    $headers = ["Authorization: Basic YWRtaW46dVx9TVs2enpBVUB3OFlMeA==",
                                "Content-Type: application/json"];
            
                    $url ='https://cargomation.com:5200/redis/apinvoice/compare'; 
                    $result = $this->postAuth($url,$payload,$headers);
                }
               }
                
            }
           }
           
            //$checkIFExist = $this->getSingleCWResponse($_SESSION['user'],$process_id);
            //if(empty($checkIFExist)){
            //     $arr = array(
            //         "user_id" => strval($user_id),
            //         "jsonstring"=>(int) $process_id
            //     );
    
            //     $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
            //     $headers = ["Authorization: Basic YWRtaW46dVx9TVs2enpBVUB3OFlMeA==",
            //                 "Content-Type: application/json"];
        
            //     //$url ='https://cargomation.com:5200/redis/apinvoice/compare'; 
            //     //$url ='https://cargomation.com:5200/redis/apinvoice/compare';
            //     //$result = $this->postAuth($url,$payload,$headers);
            //     //print_r($payload);
            // //print_r($result);
            //}      
    }


    public function pushTOCW(){
        $user_id = $_SESSION['user'];
        $process_id = '';
        
        if(isset($_POST)){
            $process_id = $this->getMatchReportWidthID($_POST['prim_ref'])[0]->id;
            $arr = array(
                "user_id" => strval($user_id),
                "id" => (int)$process_id
            );
    
            $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
            $headers = ["Authorization: Basic YWRtaW46dVx9TVs2enpBVUB3OFlMeA==",
                        "Content-Type: application/json"];
    
            $url ='https://cargomation.com:5200/redis/apinvoice/APCargowiseChargeCode'; 
    
            $result = $this->postAuth($url,$payload,$headers);

            print_r($result);
        }
         
    }

    public function uploadAndInsert(){
        if($_FILES['file']['name'] != ''){
            $test = explode('.', $_FILES['file']['name']);
            $extension = end($test);    
            $name = $_FILES['file']['name'];
            
            $User = Model\User::getInstance($_SESSION['user']);
            $email = $User->data()->email;
           // $user_id = $User->data()->id;
            
            $newFilePath = "E:/A2BFREIGHT_MANAGER/".$email."/CW_APINVOICE/IN/";

            $location = $newFilePath.$name;
            
            $data['user_id'] = $_SESSION['user'];
            $data['filename'] = $name;
            $data['filepath'] = 'https://cargomation.com/filemanager/'.$email.'/CW_APINVOICE/IN/'.$name;
            $data['uploadedby']= $email;
            $APinvoice = Model\Apinvoice::getInstance();
            
            $APinvoice->insertMatchHeader($data);


           return "success";
        }
    }

    private function postAuth($url, $payload, $headers) {
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

    public function preview(){
        if(isset($_POST)){
            echo json_encode($this->getSingleCWResponse($_SESSION['user'],$_POST['prim_ref']));
        }
       
    }

    public function showPreview(){
        $_POST['prim_ref'] = 176;
        if(isset($_POST)){
            //$singleInvoice = $this->getSingleCWResponse($_SESSION['user'],$_POST['prim_ref']);
            $headerMatched = array();
            $headerParsed = array();
           
            $data['invoices'] = array();
            $data['apinvoice'] = array();
            $parsed = array();

            $this->View->addJS("js/apinvoice.js");
            
            $data['apinvoice'] = json_decode($this->getMatchReportWidthID($_POST['prim_ref'])[0]->match_report);
            
            $data['invoices'] = $this->getInvoices($_SESSION['user']);
            $matched =  $data['apinvoice']->HubJSONOutput->CargoWiseMatchedData;
            $parsed = $data['apinvoice']->HubJSONOutput->ParsedPDFData;
            
            if(isset($matched->CWChargeLines->ChargeLine )){
                foreach($matched->CWChargeLines->ChargeLine as $key=>$m){
                    foreach($m as $mkey=>$mval){
                        if(!in_array($mkey,$headerMatched)){
                            $headerMatched[] = $mkey;
                        }
                        
                    }   
                }
            }
           
            if(isset($parsed->ParsedPDFChargeLines->ChargeLine)){
                foreach($parsed->ParsedPDFChargeLines->ChargeLine as $key=>$m){
                    foreach($m as $mkey=>$mval){
                        if(!in_array($mkey,$headerParsed)){
                            $headerParsed[] = $mkey;
                        }  
                    }  
                }
            }
            
            $this->View->renderWithoutHeaderAndFooter("/apinvoice/preview", [
                "data"=>$data['apinvoice'],
                "headerMatched" =>$headerMatched,
                "headerParsed" =>$headerParsed, 
                "parsedData" =>$parsed,
                "apinvoice" =>$data['apinvoice'],
            ]);
        }
    }

    public function edit(){
        $data = array();
        $pdfData = array();
        if(isset($_POST)){
          $cgm = json_decode($this->getMatchReportWidthID($_POST['prim_ref'])[0]->cgm_response);
          if(is_null($cgm) || empty($cgm)){
            $data = $this->getMatchReportWidthID($_POST['prim_ref'])[0]->match_report;
          }else{
            $data = $this->getMatchReportWidthID($_POST['prim_ref'])[0]->cgm_response;
          }
          
          $pdfData = json_decode($data)->HubJSONOutput->ParsedPDFData->ParsedPDFChargeLines->ChargeLine[$_POST['index']];
          //$data =  $_POST['data']['ParsedPDFChargeLines']['ChargeLine'][$_POST['index']];
        }
        $this->View->addJS("js/apinvoice.js");
       
        $this->View->renderWithoutHeaderAndFooter("/apinvoice/edit", [
            "data" => $pdfData,
            "apinvoice"=>json_decode($data),
            "index"=>$_POST['index'],
         ]);
    }
    
    public function cwresponse(){
        $data = array();
        $data['cwresponse'] = 'Empty';
        $data['cwstatus'] = '';
        if(isset($_POST)){
         $APinvoice = Model\Apinvoice::getInstance();
         $data['invoices'] = $APinvoice->getSingleCWResponse($_SESSION['user'],$_POST['prim_ref']);
         if(!empty($data['invoices'][0]) && isset($data['invoices'][0]->cw_response)){
            $data['cwresponse'] = $data['invoices'][0]->cw_response;
            $data['cwstatus'] = $data['invoices'][0]->cw_response_status;
         }
        }
         
        $this->View->addJS("js/apinvoice.js");
       
        $this->View->renderWithoutHeaderAndFooter("/apinvoice/cwresponse", [
            "data" => $data['cwresponse'],
            "cwstatus" => $data['cwstatus']
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
            $data['cgm'] = json_encode($_POST['apinvoice']);
            $data['prim_ref'] = $_POST['prim_ref'];
            
            $APinvoice->addToCGM_Response($data);
        }
    }

    public function getSingleCWResponse($user_id,$prim_ref){
        $APinvoice = Model\Apinvoice::getInstance();
        return $APinvoice->getSingleCWResponse($user_id,$prim_ref);
    }
    public function geTempData(){
        $APinvoice = Model\Apinvoice::getInstance();
        return $APinvoice->getMatchReport(176);
    }

    public function geTempDataV2($process_id){
        $APinvoice = Model\Apinvoice::getInstance();
        return $APinvoice->getMatchReport($process_id);
    }

    public function getInvoices($user_id){
        $APinvoice = Model\Apinvoice::getInstance();
        return $APinvoice->getInvoices($user_id);
    }

    public function getMatchReportWidthID($prim_ref){
        $APinvoice = Model\Apinvoice::getInstance();
        return $APinvoice->getMatchReportWidthID($prim_ref);
    }

    public function getInvoicesSuccess($user_id){
        $APinvoice = Model\Apinvoice::getInstance();
        return $APinvoice->getInvoicesSuccess($user_id);
    }

    public function getSingleInvoice($user_id){
        $APinvoice = Model\Apinvoice::getInstance();
        return $APinvoice->getSingleInvoice($user_id); 
    }

    public function getSingleInvoiceBytype($user_id,$type){
        $APinvoice = Model\Apinvoice::getInstance();
        return $APinvoice->getSingleInvoiceBytype($user_id,$type); 
    }

    

    public function getLastID(){
        $APinvoice = Model\Apinvoice::getInstance();
        $lastID = $APinvoice->getLastID();
        return $lastID[0]->lastid;
    }

    public function getAPCompleteCount($user_id){
        $APinvoice = Model\Apinvoice::getInstance();
        $twoJoint = $APinvoice->getAPCompleteCount($user_id);
        return $twoJoint;
    }

    public function getAPQUECount($user_id){
        $APinvoice = Model\Apinvoice::getInstance();
        $twoJoint = $APinvoice->getAPQUECount($user_id);
        return $twoJoint;
    }

    public function getAPArchiveCount($user_id){
        $APinvoice = Model\Apinvoice::getInstance();
        $twoJoint = $APinvoice->getAPArchiveCount($user_id);
        return $twoJoint;
    }
    

    public function getCompleteFilter($user_id){
        $APinvoice = Model\Apinvoice::getInstance();
        $twoJoint = $APinvoice->getAPCompleteCount($user_id);
        return $twoJoint;
    }
    
    public function chartData($user_id){
        $APinvoice = Model\Apinvoice::getInstance();
        $chartData = $APinvoice-> chartData($user_id);
        return $chartData;
    }
    public function getParseINV($user_id){
        $APinvoice = Model\Apinvoice::getInstance();
        $parsedINV = $APinvoice-> getParseINV($user_id);
        return $parsedINV;
    }

    public function saveToArchive(){
        $APinvoice = Model\Apinvoice::getInstance();
        if(isset($_POST['process_id'])){
            $APinvoice->saveToArchive($_POST['process_id']);
        }
    }

    public function getApMacroLick(){
        $APinvoice = Model\Apinvoice::getInstance();
        if(isset($_POST['shipmentid'])){
            $macro = $APinvoice->getApMacroLick($_POST['shipmentid']);
            
            if(isset($macro[0])){
                echo json_encode($macro[0]->macro_link);
            }
        }
    }


    public function checkSite(){  
        // if($url == NULL) return false;
        $url = $_POST['url'];
        $cdata = array();  
        $ch = curl_init($url);  
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
         curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);


        $data = curl_exec($ch);  
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
        $redirectedUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);  
        
        if($httpcode >= 200 && $httpcode < 300){  
             echo 'valid';
        } else {  
            echo 'invalid';  
        }  
    }

    public function test(){
        $url = 'https://cargomation.com:5200/redis/apinvoice/getdata';
        $arr = [
            "draw" => '1',
            "start" => 0,
            "length" => 10,
            "user_id" => (string)$_SESSION['user'],
        ];

        $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
        $headers = ["Authorization: Basic YWRtaW46dVx9TVs2enpBVUB3OFlMeA==",
                    "Content-Type: application/json"];
        $result = $this->postAuth($url, $payload, $headers);
        $json_data = json_decode($result);
        
        if($json_data->status != '200') {
            echo json_encode($json_data);
            exit;
        }
        
        $array_data = array(
            "draw"            => $json_data->draw,  
            "recordsTotal"    => $json_data->recordsTotal,  
            "recordsFiltered" => $json_data->recordsTotal,
            "data"            => $this->reprocessData(json_decode($json_data->data))
        );
       
    }

    public function reprocessData($data){
        $retData = array();
        $invoiceHeader = array();
        $splitInvoice = array();
        $data['invoices'] = $data;//$this->getSingleInvoice($_SESSION['user']);
        
        if(isset($_POST['type'])){
            $data['invoices'] = $this->getSingleInvoiceBytype($_SESSION['user'],$_POST['type']);
        }

        foreach($data['invoices'] as $value){
            $splitInvoice[$value->process_id][] = $value;
        }
       //echo"<pre>";
       $retData['completedCount'] = $completeCount = $this->getAPCompleteCount($_SESSION['user']);
       $retData['que'] =  $this->getAPQUECount($_SESSION['user']);
       $retData['archive'] = $this->getAPArchiveCount($_SESSION['user']);

        foreach($splitInvoice as $key=>$v){
            $invoiceHeader['invoice'] = array();
            foreach($v as $value){
                $actionbtn = '';
                $cstatus = '';
                $invoiceHeader['invoice_num'] = '';
                $invoiceHeader['invoice_report'] ='';
                $invoiceHeader['invoice_response'] ='';
                $invoiceHeader['invoice_status'] = '';
                if(!empty($value->match_reports)){
                    if(!empty($value->match_reports->match_report1)){
                        $mactch1 =$value->match_reports->match_report1;
                        $parsed = json_decode($value->match_reports->match_report1);
                        $parsedChargline = $parsed->HubJSONOutput->ParsedPDFData->ParsedPDFChargeLines;

                        if(isset($parsedChargline->ChargeLine) && !empty($parsedChargline->ChargeLine)){
                    
                            foreach($parsedChargline->ChargeLine as $chline){
                                $invoiceHeader['invoice_num'] = $chline->InvoiceNumber ;
                                $invoiceHeader['invoice_report'] = $parsed->HubJSONOutput->MatchReport->Status;
                                $invoiceHeader['invoice_response'] = "ready";
                                $invoiceHeader['invoice_status'] = "complete";
                            }
                        }
                        
                        if(!is_null($mactch1->cw_response) || !empty($mactch1->cw_response)){
                            $actionbtn = "<button type='button' class='btn btn-block btn-outline-success cwresmodal' data-pid='".$value->process_id."'>CW Response</button>";
                        }

                        $invoiceHeader['invoice'][]  = "INVOICE_{$invoiceHeader['invoice_num']},
                                                        {$invoiceHeader['invoice_report']},
                                                        <span class='badge bg-danger'>{$invoiceHeader['invoice_response']}</span>,
                                                        <span class='badge bg-warning'>{$invoiceHeader['invoice_status']}</span>,
                                                        ".$actionbtn.",".$mactch1->cw_response_status;
                        $jobnum = isset($mactch1->sec_ref) ? json_decode($mactch1->sec_ref) : '';
                        $actionbtn = "<div class='container'><div class='row'><div class='col-xs-6'></div><div class='col-xs-6'><button data-pd='{$value->process_id}' type='button' class='toarchive btn btn-block btn-outline-danger'>Archive</button></div></div></div>";                               
                        
                        
                        if(!is_null($mactch1->cw_response_status)){
                            $cstatus = $mactch1->cw_response_status ==='Success' ? 'Complete' : $cstatus = $mactch1->cw_response_status;
                        }else{
                            if(is_null($mactch1->status) || empty($mactch1->status)){
                                $cstatus = 'Processing';
                            }else{
                                $cstatus =  $mactch1->status;
                            }
                        }

                    }

                }
                
            }

            $retData['data'][] = array(
                "Process ID" => $value->process_id,
                "File Name" => $value->filename,
                "Doc Number" => (!isset($jobnum->doc_number) ? 'Empty' : (is_null($jobnum->doc_number) ? 'Empty ' : $jobnum->doc_number)) ,
                "Date Uploaded"=> date('d/m/y H:i a', strtotime($value->dateuploaded)),
                "Uploaded By" => $value->uploadedby,
                "Action"=> $actionbtn,
                "Status"=> $cstatus,
                "invoices" => $invoiceHeader['invoice'],
                "pid" =>$value->process_id
            );
        }
        //$this->reprocessReportONSuccess();
       // print_r($retData);
        echo json_encode($retData);
    }

    
}