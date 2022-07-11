<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;


class Docregister extends Core\Controller {
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
        $this->View->addJS("js/docregister.js");

        $imageList = (Object) Model\User::getProfile($user);
        $profileImage = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }

        $this->View->renderTemplate("/docregister/index", [
            "title" => "Document Register",
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

    public function validateToken($token) {
        if(isset($token)){
            return true;
        }
        return false;
    }

    // main upload function used above
    // upload the bootstrap-fileinput files
    // returns associative array
    public function upload($param) {
        $User = Model\User::getInstance($param);
        $email = $User->data()->email;
        $user_id = $User->data()->id;
        // get client admin email
        if(!empty($User->getSubAccountInfo($user_id))) {
            $sub_account = $User->getSubAccountInfo($user_id);
            // "user email" change to "client email"
            $email = $sub_account[0]->client_email;
        }
        //Setup our new file path
        $newFilePath = "E:/A2BFREIGHT_MANAGER/" . $email . "/CW_INVOICE/IN/";
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

    public function allDocs(){
        $retData = array();
        $docs = array();
        $docmatch ='';
       
        $docreg = $this->getDocReportReg($_SESSION['user']);
        //echo"<pre>";
      // print_r($docreg);
       //exit;
        if(!empty($docreg)){
            
            foreach($docreg as $docval){
                $docMasterBill = '';
                $docHouseBill = '';
                $docIdentified = '';
                if(isset($docval->match_report)){
                    $docmatch = json_decode($docval->match_report);
                    //print_r($docmatch);
                    if(isset($docmatch->HubJSONOutput->ParsedPDFData)){
                        if(isset($docmatch->HubJSONOutput->ParsedPDFData->ParsedPDFHeader)){
                            if(isset($docmatch->HubJSONOutput->ParsedPDFData->ParsedPDFHeader->mbl_number)){
                                $docMasterBill = $docmatch->HubJSONOutput->ParsedPDFData->ParsedPDFHeader->mbl_number;
                            }
                            if(isset($docmatch->HubJSONOutput->ParsedPDFData->ParsedPDFHeader->hbl_number)){
                                $docHouseBill = $docmatch->HubJSONOutput->ParsedPDFData->ParsedPDFHeader->hbl_number;
                            }
                        }
                    }
                }
               
                $docs = '<div class="d-inline-block w-45">
                        <h4>Master Bill</h4><br>
                        <span><strong>'.$docMasterBill.'</strong></span><br>
                        <span>Match Status </span> <span class="badge badge-success">Ready</span><br>
                        <span>Upload Status</span>
                        <i class="far fa-check-circle"></i>
                    </div>,
                    <div class="d-inline-block w-45">
                        <h4>House Bill</h4><br>
                        <span>'.$docHouseBill.'</span><br>
                    </div>,
                    <div class="d-inline-block w-45">
                        <h4>Other Documents Identified</h4><br>
                        <span>'.$docIdentified.'</span><br>
                    </div>,
                    <div class="d-inline-block w-45">
                        <button data-prim_ref="'.$docval->process_id.'" type="button" class="btn btn-block btn-outline-info btn-xs custom viewdoc" >Preview Match Report</button><br>
                        <button type="button" class="btn btn-block btn-outline-info btn-xs custom" data-toggle="modal" data-target="#modal-lg-error">Send To Cargowise</button><br>
                        <button data-prim_ref="'.$docval->process_id.'" type="button" class="btn btn-block btn-outline-success btn-xs custom cwresponse" data-toggle="modal" data-target="#modal-lg-error">View CW Response</button><br>
                    </div>';
                
                $retData['data'][] = array(
                    "Process ID" => $docval->process_id,
                    "File Name" => $docval->dfilename,
                    "Doc Number" => $docval->doc_type,
                    "Date Uploaded"=> $docval->dateuploaded,
                    "Uploaded By" => $docval->uploadedby,
                    "Action"=> '<div class="btn-group ">
                                    <button type="button" class="btn btn-default">Action</button>
                                    <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="true">
                                    <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu " role="menu" style="position: absolute; transform: translate3d(65px, 35px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-start">
                                    <a class="dropdown-item" href="#">Push to Cargowise</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-xl">View File</a>
                                    <div class="dropdown-divider"></div>
                                    <a data-pd="'.$docval->process_id.'" class="dropdown-item toarchive" href="#">Delete</a>
                                    </div>
                                    </div>',
                    "Status"=> "Processing",
                    "docs"=> $docs 
                );
            }
        }
        // exit;
        //$data['invoices'] = $this->getInvoices($_SESSION['user']);
        
        // foreach($data['invoices'] as $value){
        //     $retData['data'][] = array(
        //         "Process ID" => $value->process_id,
        //         "File Name" => $value->filename,
        //         "Doc Number" => "empty",
        //         "Date Uploaded"=> date("d/m/Y"),
        //         "Uploaded By" => $value->uploadedby,
        //         "Action"=> "<div class='container'><div class='row'><div class='col-xs-6'></div><div class='col-xs-6'><button type='button' class='btn btn-block btn-outline-danger'>Delete</button></div></div></div>",
        //         "Status"=> "Processing",
        //         "invoices"=>''
        //     );
        // }
       //exit;
        echo json_encode($retData);
    }

    public function preview($prim_ref=""){
       
        $url = explode("/",$_GET['url']);
        $prim_ref = end($url);
        $doc = $this->getDocReportRegSingle($_SESSION['user'],$prim_ref);
        $hbl_numbers = array();
        $container_details = array();
        $tableheader = array();
        $fieldlist=array();
        $filename = '';
        $matchData = array();
        $responsedata = array();
        $mustnot = array('filename','pages','webservice_link','webservice_username','webservice_password','server_id','enterprise_id','process_id','merged_file_path','page','state_code','company_code');
      
        $jsonDecode = json_decode($this->newjson($prim_ref,$_SESSION['user']));
       
        //$jsonDecode = json_decode($this->newjson(230,181));
        $status = $jsonDecode->status;
        $message = $jsonDecode->message;

        $responsedata['process_id'] = $prim_ref;
        $responsedata['user_id'] = $_SESSION['user'];
        $responsedata['status'] = $status;
        $responsedata['logs'] = $message;
        $responsedata['response'] = json_encode($jsonDecode);
        $responsedata['action_type'] = 'preview documents';

        $this->insertLogs($responsedata);
        // echo"<pre>";
        
        if(isset($this->getCGMresponse($prim_ref)[0]) && !empty($this->getCGMresponse($prim_ref)[0]->cgm_response)){
            $jsonDecode->data = $this->getCGMresponse($prim_ref)[0]->cgm_response;
        }
      
        // $jsonDecode->data='{"data":"{\"MatchReportArray\": [{\"HubJSONOutput\":{\"CargoWiseMatchedData\":{\"CWHeader\":null,\"CWLines\":null},\"ParsedPDFData\":{\"ParsedPDFHeader\":{\"goods_description\":\"CRAWLER SHOE BRADKEN PO YDC-514248-3\",\"shipper\":\"BRADKEN (XUZHOU) METAL EQUIPMENT MANUFACTURING CO., LTD\",\"volume\":\"36.440\",\"gross_weight\":\"77,800.000\",\"gross_weight_uom\":\"KG\",\"consignee\":\"BRADKEN RESOURCES PTY LTD\",\"shipper_org_code\":\"BRAXUZSHA\",\"consignee_org_code\":\"BRARESBAS\",\"incoterm\":\"FREIGHT COLLECT\",\"marks_numbers\":\"N\/M\",\"package_count\":\"40 PALLETS\",\"package_count_uom\":\"PLT\",\"hbl_number\":\"QD22D00602\",\"coloader\":\"SILA GLOBAL PTY LTD\",\"port_destination\":\"SYDNEY, AUSTRALIA\",\"number_original\":\"THREE(3)\",\"port_origin\":\"QINGDAO, CHINA\",\"filename\":\"HBL 799210291130.pdf\",\"page\":\"1\",\"doc_type\":\"HBL\",\"process_id\":\"315\",\"release_type\":\"OBR\",\"state_code\":\"NSW\",\"webservice_link\":\"https:\/\/siltrnservices.wisegrid.net\/eAdaptor\",\"webservice_username\":\"silbne\",\"webservice_password\":\"Sil@bne18\",\"server_id\":\"TRN\",\"enterprise_id\":\"SIL\",\"company_code\":\"SIL\"},\"ParsedPDFLines\":{\"ParsedPDFLine\":[{\"CONTAINER_NUMBER\":\"DFSU3139668\",\"SEAL\":\"TSH1126361\",\"CONTAINER_TYPE\":\"20GP\",\"CHARGEABLE_WEIGHT\":\"19450.000 KGS\",\"CHARGEABLE_WEIGHT_UOM\":\"KG\",\"VOLUME\":\"9.110 CBM\",\"VOLUME_UOM\":\"PLT\",\"PACKAGE_COUNT\":\"10 PALLETS\"},{\"CONTAINER_NUMBER\":\"DFSU1698137\",\"SEAL\":\"TSH1126362\",\"CONTAINER_TYPE\":\"20GP\",\"CHARGEABLE_WEIGHT\":\"19450.000 KGS\",\"CHARGEABLE_WEIGHT_UOM\":\"KG\",\"VOLUME\":\"9.110 CBM\",\"VOLUME_UOM\":\"PLT\",\"PACKAGE_COUNT\":\"10 PALLETS\"},{\"CONTAINER_NUMBER\":\"CAAU2010774\",\"SEAL\":\"TSH1126287\",\"CONTAINER_TYPE\":\"20GP\",\"CHARGEABLE_WEIGHT\":\"19450.000 KGS\",\"CHARGEABLE_WEIGHT_UOM\":\"KG\",\"VOLUME\":\"9.110 CBM\",\"VOLUME_UOM\":\"PLT\",\"PACKAGE_COUNT\":\"10 PALLETS\"},{\"CONTAINER_NUMBER\":\"TLLU2704155\",\"SEAL\":\"TSH1126289\",\"CONTAINER_TYPE\":\"20GP\",\"CHARGEABLE_WEIGHT\":\"19450.000 KGS\",\"CHARGEABLE_WEIGHT_UOM\":\"KG\",\"VOLUME\":\"9.110 CBM\",\"VOLUME_UOM\":\"PLT\",\"PACKAGE_COUNT\":\"10 PALLETS\"}]}}}},{\"HubJSONOutput\":{\"CargoWiseMatchedData\":{\"CWHeader\":null,\"CWLines\":null},\"ParsedPDFData\":{\"ParsedPDFHeader\":{\"port_origin\":\"QINGDAO, CHINA\",\"port_destination\":\"SYDNEY\",\"volume\":\"36.44000CBM\",\"volume_uom\":\"M3\",\"package_count\":\"40 PALLETS\",\"package_count_uom\":\"PLT\",\"goods_description\":\"CRAWLER SHOE BRADKEN PO YDC-514248-3\",\"shipper\":\"HONOUR LANE SHIPPING LTD.QINGDAO BRANCH\",\"shipper_org_code\":\"HONLANCKG\",\"consignee\":\"SILA GLOBAL PTY LTD\",\"consignee_org_code\":\"CSIINTBNE\",\"carrier\":\"T.S. LINES\",\"carrier_org_code\":\"TSLINE_WW\",\"gross_weight\":\"77800.00000KGS\",\"gross_weight_uom\":\"KG\",\"voyage_number\":\"2208S\",\"incoterm\":\"FREIGHT PREPAID\",\"mbl_number\":\"799210291130\",\"filename\":\"MBL 799210291130.pdf\",\"page\":\"1\",\"doc_type\":\"MBL\",\"process_id\":\"316\",\"state_code\":\"QLD\",\"webservice_link\":\"https:\/\/siltrnservices.wisegrid.net\/eAdaptor\",\"webservice_username\":\"silbne\",\"webservice_password\":\"Sil@bne18\",\"server_id\":\"TRN\",\"enterprise_id\":\"SIL\",\"company_code\":\"SIL\"},\"ParsedPDFLines\":{\"ParsedPDFLine\":[{\"CONTAINER_NUMBER\":\"CAAU2010774\",\"SEAL\":\"TSH1126287\",\"CONTAINER_TYPE\":\"20GP\",\"CHARGEABLE_WEIGHT_UOM\":\"KG\",\"VOLUME_UOM\":\"PKG\"},{\"CONTAINER_NUMBER\":\"DFSU1698137\",\"SEAL\":\"TSH1126362\",\"CONTAINER_TYPE\":\"20GP\",\"CHARGEABLE_WEIGHT_UOM\":\"KG\",\"VOLUME_UOM\":\"PKG\"},{\"CONTAINER_NUMBER\":\"DFSU3139668\",\"SEAL\":\"TSH1126361\",\"CONTAINER_TYPE\":\"20GP\",\"CHARGEABLE_WEIGHT_UOM\":\"KG\",\"VOLUME_UOM\":\"PKG\"},{\"CONTAINER_NUMBER\":\"TLLU2704155\",\"SEAL\":\"TSH1126289\",\"CONTAINER_TYPE\":\"20GP\",\"CHARGEABLE_WEIGHT_UOM\":\"KG\",\"VOLUME_UOM\":\"PKG\"}]}}}}]}","status":"200","message":"Success"}';
        // $jsonDecode->data = json_decode($jsonDecode->data)->data;

      if(!isset($jsonDecode->data)) exit;
        
        $matchArray =  json_decode($jsonDecode->data)->MatchReportArray;
       
        if(!empty($matchArray)){

            foreach($matchArray as $match){
                $jmatch = $match; 
                $parsePdfData = $jmatch->HubJSONOutput->ParsedPDFData;
                $parsePdfDataheader = $parsePdfData->ParsedPDFHeader;
                $parsePdfParsedPDFLines = $parsePdfData->ParsedPDFLines;
                $hbl_numbers = isset($parsePdfDataheader->mbl_number) ? $parsePdfDataheader->mbl_number : $parsePdfDataheader->hbl_number;
                $filename = $parsePdfDataheader->filename;
                $fieldlist=array();
                $container_details = array();
                //print_r($jmatch);
                
                foreach($parsePdfDataheader as $key=>$pdf){
                    if(!in_array($key, $mustnot)){
                        $fieldlist[ucwords(str_replace("_"," ",$key))] = $pdf;
                    }
                }
                if(isset($parsePdfParsedPDFLines->ParsedPDFLine) && is_array($parsePdfParsedPDFLines->ParsedPDFLine)){
                    foreach($parsePdfParsedPDFLines->ParsedPDFLine  as $key=>$pdfchild){ 
                        if(is_object($pdfchild)){
                            foreach($pdfchild as $pkey=>$pval){
                                $tableheader[str_replace("_"," ",$pkey)]=str_replace("_"," ",$pkey);  
                            }
                            $container_details[] = $pdfchild;
                        }else{
                            $tableheader[str_replace("_"," ",$key)]=str_replace("_"," ",$key);   
                            $container_details[$key] = $pdfchild; 
                        }
                        
                    }
                }else{
                    foreach($parsePdfParsedPDFLines as $key=>$pdfchild){ 
                        if(is_object($pdfchild)){
                            foreach($pdfchild as $pkey=>$pval){
                                $tableheader[str_replace("_"," ",$pkey)]=str_replace("_"," ",$pkey);  
                            }
                            $container_details[] = $pdfchild;
                        }else{
                            $tableheader[str_replace("_"," ",$key)]=str_replace("_"," ",$key);   
                            $container_details[$key] = $pdfchild; 
                        }
                        
                    }
                }
        
                $User = Model\User::getInstance($_SESSION['user']);
                $email = $User->data()->email;

                $groupByCat = array(
                    'doc_type'=>array('name'=>'Doc Type','order'=>0),
                    'hbl_number'=>array('name'=>'Hbl Number','order'=>1),
                    'mbl_number'=>array('name'=>'Mbl Number','order'=>2),
                    'incoterm'=>array('name'=>'Incoterm','order'=>3),
                    'goods_description'=>array('name'=>'Goods Description','order'=>4),
                    'marks_numbers'=>array('name'=>'Marks Numbers','order'=>5),
                    'release_type'=>array('name'=>'Release Type','order'=>6),
                    'number_original'=>array('name'=>'Number Original','order'=>7),
                    'volume'=>array('name'=>'Volume','order'=>8),
                    'volume_uom'=>array('name'=>'Volume Uom','order'=>9),
                    'gross_weight_uom'=>array('name'=>'Gross Weight Uom','order'=>10),
                    'gross_weight'=>array('name'=>'Gross Weight','order'=>11),
                    'gross_weight_uom'=>array('name'=>'Gross Weight Uom','order'=>12),
                    'package_count'=>array('name'=>'Package Count','order'=>13),
                    'package_count_uom'=>array('name'=>'Package Count Uom','order'=>14),
                    'port_destination_unlocode'=>array('name'=>'Port Destination Unlocode','order'=>15),
                    'port_destination'=>array('name'=>'Port Destination','order'=>16),
                    'port_origin_unlocode'=>array('name'=>'Port Origin Unlocode','order'=>17),
                    'port_origin'=>array('name'=>'Port Origin','order'=>18),
                    'shipper_org_code'=>array('name'=>'Shipper Org Code','order'=>19),
                    'shipper'=>array('name'=>'Shipper','order'=>20),
                    'consignee_org_code'=>array('name'=>'Consignee Org Code','order'=>21),
                    'consignee'=>array('name'=>'Consignee','order'=>22),
                    'coloader_org_code'=>array('name'=>'Coloader Org Code','order'=>23),
                    'coloader'=>array('name'=>'Coloader','order'=>24),
                    'carrier_org_code'=>array('name'=>'Carrier Org Code','order'=>25),
                    'carrier'=>array('name'=>'Carrier','order'=>26),
                    'transport'=>array('name'=>'Transport','order'=>27),
                    'container_mode'=>array('name'=>'Container Mode','order'=>28),
                    'voyage_number'=>array('name'=>'Voyage Number','order'=>29),
                    'vessel'=>array('name'=>'Vessel','order'=>30),
                ); 
               
                $reorder = array();

                if(!isset($fieldlist['Hbl Number'])){
                    unset($groupByCat['hbl_number']);
                }
                if(!isset($fieldlist['Mbl Number'])){
                    unset($groupByCat['mbl_number']);
                }
               
                foreach($groupByCat as $catkey=>$catval){
                    $kkey = str_replace("_"," ",$catval['name']);
                    if(isset($fieldlist[$catval['name']])){
                        $reorder[$kkey] = array('value'=>$fieldlist[$catval['name']],'order'=>$catval['order']);
                    }else{
                        $reorder[$kkey] = array('value'=>'','order'=>$catval['order']);
                    }
                }
               
                $sort = array_column($reorder, 'order');
                array_multisort($sort, SORT_ASC, $reorder);
               
                $matchData[] = array(
                    'hbl_numbers' => $hbl_numbers,
                    'container_details' => $container_details,
                    'doc_data' => isset($parsePdfData) ? $parsePdfData : '',
                    'filename'=> 'https://cargomation.com/filemanager/'.$email.'/CW_DOCREGISTER/IN/'.$filename,
                    'fieldlist' => $fieldlist,
                    'tableheader'=>$tableheader,
                    'reorderfield'=>$reorder
                );
            }
            
        }
        
        $encodedData = urlencode( $this->encryptData( $jsonDecode->data ) );
       
        $this->View->addJS("js/docregister.js");
        $this->View->renderWithoutHeaderAndFooter("/docregister/preview", [
            'hbl_numbers' => $hbl_numbers,
            'container_details' => $container_details,
            'doc_data' => isset($dochubparsedpdf) ? $dochubparsedpdf : '',
            'filename'=> $filename,
            'fieldlist' => $fieldlist,
            'tableheader'=>$tableheader,
            'matchData' =>$matchData,
            'process_id'=>$prim_ref,
            'match_arr'=> $encodedData,
            'match_arr_unencode' => $jsonDecode->data,
            'prim_ref'=>$prim_ref,
            'matchjson'=>$jsonDecode->data,
            'userid'=>$_SESSION['user'],
            'group_cat'=> $groupByCat
        ]);
    }

    public function encryptData($data){
        // Store the cipher method
        $ciphering = "AES-128-CTR";
        
        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        
        // Non-NULL Initialization Vector for encryption
        $encryption_iv = '1234567891011121';
        
        // Store the encryption key
        $encryption_key = "GeeksforGeeks";
        
        // Use openssl_encrypt() function to encrypt the data
        $encryption = openssl_encrypt($data, $ciphering,
        $encryption_key, $options, $encryption_iv);

        return $encryption;
    }

    public function decryptData($data){
        $ciphering = "AES-128-CTR";
         // Use OpenSSl Encryption method
         $iv_length = openssl_cipher_iv_length($ciphering);
         $options = 0;
        // Non-NULL Initialization Vector for decryption
        $decryption_iv = '1234567891011121';
        // Store the decryption key
        $decryption_key = "GeeksforGeeks";

        // Use openssl_decrypt() function to decrypt the data
        $decryption=openssl_decrypt ($data, $ciphering, 
                $decryption_key, $options, $decryption_iv);
        return $decryption;
    }

    //edit modal
    public function edit(){ 
        
        $data =array();
        $tableData=array();
        $collectionOfTableData = array();

        
        if(isset($_POST)){
           
            $prim_ref = $_POST['prim_ref'];
            $indexID = $_POST['matcharrayIndex'];
            $indexTable = $_POST['tableindex'];
            $indexData = $_POST['match_arr'];
            $decrypted = json_decode($indexData);//json_decode($this->decryptData(urldecode($indexData)));

            if(isset($this->getCGMresponse($prim_ref)[0]) && !empty($this->getCGMresponse($prim_ref)[0]->cgm_response)){
                $decrypted  = json_decode($this->getCGMresponse($prim_ref)[0]->cgm_response);
            }
           
            $tableMatchReport = $decrypted->MatchReportArray;
            $matchreportIndex = $tableMatchReport[$indexID];
            $matchreportTableData = $matchreportIndex->HubJSONOutput->ParsedPDFData->ParsedPDFLines;
       
            foreach($matchreportTableData as $key=>$tdata){
                $collectionOfTableData[] = $tdata;
            }
        //    echo"<pre>";
        //   print_r($indexTable);
        // //  print_r(  $matchreportIndex  );
        //  print_r($collectionOfTableData);
        //  echo"</pre>";
        //  exit;
            if(isset($collectionOfTableData[0])){
                $collectionOfTableData = $collectionOfTableData[0];
            }
            if(isset($collectionOfTableData[$indexTable]) && is_object($collectionOfTableData[$indexTable])){
               $tableData=$collectionOfTableData[$indexTable];
            }else{
                if(isset($collectionOfTableData[$indexID][$indexTable])){
                    $tableData=$collectionOfTableData[$indexID][$indexTable];
                }
            }
          
        }
        $this->View->addJS("js/docregister.js");
        $this->View->renderWithoutHeaderAndFooter("/docregister/edit", [
            "data"=>$decrypted,
            "tableData"=>$tableData
        ]);
    }
    // public function edit(){ 
        
    //     $data =array();
    //     $tableData=array();
    //     $collectionOfTableData = array();
    //     $url = explode("?",$_SERVER['REQUEST_URI']);
       
    //     if(!isset(  $url[1] ))exit;
    //     $data = $url[1];
    //     $urlindex = explode("&",$url[1]);
    //     $indexID = $urlindex[0];
    //     $indexTable = $urlindex[1];
    //     $indexData = $urlindex[2];
    //     $decrypted = json_decode($this->decryptData(urldecode($indexData)));
    //     if(empty($decrypted))exit;
        
    //     // echo"<pre>";
    //     // print_r($decrypted);
    //     // exit;
    //     $tableMatchReport = $decrypted->MatchReportArray;
    //     $matchreportIndex = $tableMatchReport[$indexID];
    //     $matchreportTableData = $matchreportIndex->HubJSONOutput->ParsedPDFData->ParsedPDFLines;
       
    //     foreach($matchreportTableData as $key=>$tdata){
    //         $collectionOfTableData[] = $tdata;
    //     }
    //     $tableData=$collectionOfTableData[$indexTable];
        
    //     $this->View->addJS("js/docregister.js");
    //     $this->View->renderWithoutHeaderAndFooter("/docregister/edit", [
    //         "data"=>$decrypted,
    //         "tableData"=>$tableData
    //     ]);
    // }

    //save data to cgm
    public function sendToAPI(){
        $toPass = array();
        $toPassHolder = array();
        if(isset($_POST)){
            foreach($_POST['data'] as $key=>$val){
                foreach($val as $vkey=>$vval){
                    $toPass[$vkey][]=$vval;
                }
            }
         
            if($_POST['type'] === 'table'){
                foreach($_POST['data'] as $key=>$val){
                    foreach($val as $vkey=>$vval){
                        $toPass[$vkey]=$vval;
                    }
                } 
               
                if(isset($_POST['docregister']['MatchReportArray'][$_POST['parseindex']]['HubJSONOutput']['ParsedPDFData']['ParsedPDFLines']['ParsedPDFLine'][$_POST['tableindex']])){
                    $_POST['docregister']['MatchReportArray'][$_POST['parseindex']]['HubJSONOutput']['ParsedPDFData']['ParsedPDFLines']['ParsedPDFLine'][$_POST['tableindex']]=$toPass;
                }else{
                    $_POST['docregister']['MatchReportArray'][$_POST['parseindex']]['HubJSONOutput']['ParsedPDFData']['ParsedPDFLines']['ParsedPDFLine']=$toPass;
                }        
            }else{
                if(isset($this->getCGMresponse($_POST['prim_ref'])[0]) && !empty($this->getCGMresponse($_POST['prim_ref'])[0]->cgm_response)){
                    $_POST['docregister']  = $this->getCGMresponse($_POST['prim_ref'])[0]->cgm_response;
                }
                $_POST['docregister'] = json_decode($_POST['docregister']);
                //$_POST['docregister']->MatchReportArray[$_POST['parseindex']]->HubJSONOutput->ParsedPDFData->ParsedPDFHeader = $toPass;
                foreach($toPass as $key=>$tpval){
                    //$_POST['docregister']->MatchReportArray[$_POST['parseindex']]->HubJSONOutput->ParsedPDFData->ParsedPDFHeader->$key=$tpval;
                    if($key !== 'undefined'){
                        foreach($tpval as $tpvalkey=>$tpvalval){
                                $_POST['docregister']->MatchReportArray[$tpvalkey]->HubJSONOutput->ParsedPDFData->ParsedPDFHeader->$key=$tpvalval;
                        }
                    }
                }
            }
            echo '<pre>';
            print_r($_POST['docregister']);
            //exit;
            //$_POST['apinvoice']['HubJSONOutput']['ParsedPDFData']['ParsedPDFChargeLines']['ChargeLine'][$_POST['index']] = $toPass;
            $data['cgm'] = json_encode($_POST['docregister']);
            $data['prim_ref'] = $_POST['prim_ref'];
            
           $APinvoice = Model\DocRegister::getInstance();
           $APinvoice->addToCGM_Response($data);
        }
    }

    public function cwresponse($prim_ref=""){
       
        $url = explode("/",$_GET['url']);
        $prim_ref = end($url);
        $doc = $this->getcwresponse($prim_ref);
        $data = array();
        
        if(!empty($doc)){
            $data =$doc[0]->cw_response;
        }
        
        $this->View->addJS("js/docregister.js");
        $this->View->renderWithoutHeaderAndFooter("/docregister/cwresponse", [
           'data' =>$data
        ]);
    }

    public function customUpload(){
        $pid = array();
        if(isset($_POST['file'][0])){
           
            foreach(json_decode($_POST['file'][0])->processID as $process){
                $pid[] = '"'.(string)$process.'"';
            }
            
        }
        
        $User = Model\User::getInstance($_SESSION['user']);
        $email = $User->data()->email;
        $filearray = array();

        $newFilePath = "E:/A2BFREIGHT_MANAGER/".$email."/CW_DOCREGISTER/IN/";

        if (!file_exists($newFilePath)) {
            mkdir($newFilePath, 0777, true);
        }

        foreach($_FILES['file']['name'] as $key=>$file){
            $name = $file;
            $location = $newFilePath.$name;
            move_uploaded_file($_FILES['file']['tmp_name'][$key], $location);
            $path = "https://cargomation.com/filemanager/";
            $folderpath = "/CW_DOCREGISTER/IN/";
            //$filearray[]="'https://cargomation.com/filemanager/'.$email.'/CW_DOCREGISTER/IN/'.$name'";
            $filearray[]='"'.$path.$email.$folderpath.$name.'"';
        }
        
        $arr  = array(
            'file'=> "[".implode(",",$filearray)."]",
            'user_id' => (string)$_SESSION['user'],
            'process_id' =>  "[".implode(",",$pid)."]"
        );
        
        $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
         
        $url ='https://cargomation.com:8002/compare'; 
    
       $result = $this->post($url, $arr, '');

        $ret = array();
        $ret['result'] = $result;
        $ret['prim_ref'] = $this->getLastID();

        echo json_encode($ret);
    }

    public function uploadAndInsert(){
       
        $User = Model\User::getInstance($_SESSION['user']);
        //$APinvoice = Model\DocRegister::getInstance();
        $email = $User->data()->email;
        $listOfProcessID = array();
        
        $newFilePath = "E:/A2BFREIGHT_MANAGER/".$email."/CW_DOCREGISTER/IN/";

        if(isset($_FILES['file']['name'])){
            $fl=array();
            foreach($_FILES['file']['name'] as $key=>$file ){
                $name = $file;
                $location = $newFilePath.$name;

                $data['user_id'] = $_SESSION['user'];
                $data['filename'] = $name;
                $data['filepath'] = 'https://cargomation.com/filemanager/'.$email.'/CW_DOCREGISTER/IN/'.$name;
                $data['uploadedby']= $email;
                
                $this->insertDoc($data);
                $listOfProcessID[] = $this->getLastID();
                //$fl['filename'][] = $_FILES['file']['name'][$key];
                //$fl['tmp_name'][] = $_FILES['file']['tmp_name'][$key];
            }
            //$this->customUpload($fl,$this->getLastID());
        }
        echo json_encode(array("processID"=>$listOfProcessID));
        // exit;
        // if($_FILES['file']['name'] != ''){
        //     $test = explode('.', $_FILES['file']['name']);
        //     $extension = end($test);    
        //     $name = $_FILES['file']['name'];
            
        //     $User = Model\User::getInstance($_SESSION['user']);
        //     $email = $User->data()->email;
        //    // $user_id = $User->data()->id;
            
        //     $newFilePath = "E:/A2BFREIGHT_MANAGER/".$email."/CW_DOCREGISTER/IN/";

        //     $location = $newFilePath.$name;
            
        //     $data['user_id'] = $_SESSION['user'];
        //     $data['filename'] = $name;
        //     $data['filepath'] = 'https://cargomation.com/filemanager/'.$email.'/CW_DOCREGISTER/IN/'.$name;
        //     $data['uploadedby']= $email;
        //     $APinvoice = Model\Apinvoice::getInstance();
            
        //     $this->insertDoc($data);

        //    return "success";
        // }
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

    public function getDocReportReg($user_id){
        $APinvoice = Model\DocRegister::getInstance();
        return $APinvoice->getDocReportReg($user_id);
    }

    public function getDocReportRegSingle($user_id,$prim_ref){
        $APinvoice = Model\DocRegister::getInstance();
        return $APinvoice->getDocReportRegSingle($user_id,$prim_ref);
    }

    public function insertDoc($data){
        $Docregister = Model\DocRegister::getInstance();
        return $Docregister->insertDoc($data);
    }

    public function getLastID(){
        $APinvoice = Model\DocRegister::getInstance();
        $lastID = $APinvoice->getLastID();
        return $lastID[0]->lastid;
    }

    public function insertLogs($data){
        $APinvoice = Model\DocRegister::getInstance();
        $lastID = $APinvoice->insertLogs($data);
       
    }

    public function getcwresponse($prim_ref){
        $APinvoice = Model\DocRegister::getInstance();
        $lastID = $APinvoice->getcwresponse($prim_ref);
        return $lastID;
    }
    
    
    public function newjson($process_id,$user_id){
        $url = 'https://cargomation.com:5200/redis/apinvoice/shipmentreg_hblmbl';
        $arr = [
            'process_id' =>(int)$process_id,
            'user_id'=>(string)$user_id
        ];
        
        $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
        
        $headers = ["Authorization: Basic YWRtaW46dVx9TVs2enpBVUB3OFlMeA==",
                    "Content-Type: application/json"];
      
        $result = $this->postAuth($url, $payload, $headers);
        return $result;
    }

    public function pushToCargowise(){
        $user_id = $_SESSION['user'];
        $process_id = '';
        $data = array();
        if(isset($_POST)){
            $match_response = json_decode($this->newjson($_POST['process_id'],$user_id));//$this->getCMByprim_ref($_POST['prim_ref'])[0]->id;
            
            if(isset($this->getCGMresponse($_POST['process_id'])[0]) && !empty($this->getCGMresponse($_POST['process_id'])[0]->cgm_response)){
                $match_response->data = $this->getCGMresponse($_POST['process_id'])[0]->cgm_response;
            }
            
            $decode_respose = $match_response->data;
           
            $arr = array(
                "string_hubjsonArray" => $decode_respose,
            );
    
            $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
            $headers = ["Authorization: Basic YWRtaW46dVx9TVs2enpBVUB3OFlMeA==",
                        "Content-Type: application/json"];
    
            $url ='https://cargomation.com:5200/redis/apinvoice/CargowiseShipmentReg'; 
            
            $result = $this->postAuth($url,$payload,$headers);
            $decoded = json_decode($result);
           
            $data['process_id'] = $_POST['process_id'];
            $data['user_id'] =  $user_id ;
            $data['status'] = $decoded->status;
            $data['logs'] = $decoded->message;
            $data['response'] =  $decoded->data;
            $data['action_type'] = 'Push To Cargowise Button';

           echo json_encode($data);
        }
         
    }

    public function getCMByprim_ref(){
        $APinvoice = Model\DocRegister::getInstance();
        $lastID = $APinvoice->getLastID();
        return $lastID[0]->lastid;
    }

    public function getCGMresponse($prim_ref){
        $APinvoice = Model\DocRegister::getInstance();
        return $APinvoice->getCGMresponse($prim_ref);
    }

    public function updateParseInput($prim_ref,$parse_input){
        $APinvoice = Model\DocRegister::getInstance();
        $APinvoice->updateParseInput($prim_ref,$parse_input);
       // return $lastID[0]->lastid;
    }

    public function setParseInput(){
        $_POST['parse_input'] = '{\"HBL\": [{\"goods_description\": \"DESMODUR T80 TANK TRUCK UN NO. 2078 TOLUENE DIISOCYANATE CLASS 6.1, II, IMDG-CODE\", \"carrier\": null, \"coloader\": \"SILA GLOBAL PTY LTD\", \"consol_number\": null, \"hbl_number\": \"STL22008755\", \"coload_number\": null, \"payment\": null, \"container_number\": null, \"shipper\": \"COVESTRO (HONG KONG) LIMITED\", \"port_origin\": \"SHANGHAI\", \"number_original\": \"THREE\", \"consignee\": \"COVESTRO PTY LTD\", \"incoterm\": \"FREIGHT PREPAID\", \"port_destination\": \"MELBOURNE\", \"gross_weight\": null, \"table\": {\"container_number\": [\"WSDU6001792\"], \"seal\": [null], \"container_type\": [\"20TK*1\"], \"chargeable_weight\": [\"23260 KGS\"], \"volume\": [\"24CBM\"], \"package_count\": [null]}, \"filename\": \"STL22008755.pdf\", \"page\": 1, \"release_type\": \"OBR\", \"webservice_link\": \"https:\/\/a2btrnservices.wisegrid.net\/eAdaptor\/  \", \"webservice_username\": \"A2B\", \"webservice_password\": \"Hw7m3XhS\", \"server_id\": \"TRN\", \"enterprise_id\": \"A2B\", \"company_code\": \"SYD\", \"process_id\": \"185\"}]}';
        if(isset($_POST)){
            $this->updateParseInput($_POST['prim_ref'],json_encode($_POST['parse_input']));
        }
    }

    public function toArchive(){
        if(isset($_POST['process_id'])){
            $APinvoice = Model\DocRegister::getInstance();
            $lastID = $APinvoice->toArchive($_POST['process_id']);
        }
       
        //return $lastID[0]->lastid;
    }

    public function archiveAll(){
       
        $user_id = $_SESSION['user'];
        $process_id = '';

        //if(isset($_POST)){
           // $match_response = $this->newjson($_POST['process_id'],$user_id);//$this->getCMByprim_ref($_POST['prim_ref'])[0]->id;
           // $decode_respose = json_decode($match_response)->data;
           
            $arr = array(
                "user_id" => (string)$user_id,
            );
    
            $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
            $headers = ["Authorization: Basic YWRtaW46dVx9TVs2enpBVUB3OFlMeA==",
                        "Content-Type: application/json"];
    
            $url ='https://cargomation.com:5200/redis/apinvoice/shipmentreg_archive'; 
    
            $result = $this->postAuth($url,$payload,$headers);

            print_r($result);
            return $result;
        //}
    }

    public function getcounts(){
        $Docregister = Model\DocRegister::getInstance();
        echo json_encode($Docregister->getListCount($_SESSION['user']));
    }

    public function chartdata(){
        $Docregister = Model\DocRegister::getInstance();
        echo json_encode($Docregister->chartData($_SESSION['user']));
    }

    public function getOrgCodeByUserID(){
        $Docregister = Model\DocRegister::getInstance();
        echo json_encode($Docregister->getOrgCodeByUserID($_SESSION['user']));
    }

    public function getShipCodeByUserID(){
        $Docregister = Model\DocRegister::getInstance();
        echo json_encode($Docregister->getShipCodeByUserID($_SESSION['user']));
    }

    public function autocomplete(){
        
        $exp1 = explode('?',$_SERVER['REQUEST_URI']);
        if(isset($exp1[1])){
            $expReq = $exp1[1];
            $exp2 = explode('=',$expReq);
            if(isset($exp2[1])){
                $lvalue = $exp2[1];
                $arr = array(
                    "user_id" => (string)181,
                    "filter"=> array(
                            array(
                                "columnname"=> "org_code",
                                "type"=> "contains",
                                "value"=> (string)$lvalue,
                            "cond"=> "and"
                            )
                        )
                );
        
                $payload = json_encode($arr, JSON_UNESCAPED_SLASHES);
                $headers = ["Authorization: Basic YWRtaW46dVx9TVs2enpBVUB3OFlMeA==",
                            "Content-Type: application/json"];
         
                $url ='https://cargomation.com:5200/redis/apinvoice/getorg_codes'; 
        
                $result = $this->postAuth($url,$payload,$headers);
                
                $returnarray = array();
                $decoded = json_decode($result);
                if($decoded->message === "Success"){
                    if(!empty($decoded->data)){
                      
                        foreach(json_decode($decoded->data) as $ccode){
                            $returnarray[$ccode->org_code][] = array(
                                "code"=>$ccode->org_code,
                                "name"=>$ccode->org_name
                            );
                        }

                       echo json_encode($returnarray);
                    }
                }
            }
        }
    }
}