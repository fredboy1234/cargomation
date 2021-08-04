<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;

use GuzzleHttp\Psr7;

/**
 * Document Controller:
 *
 * @author John Alex
 * @since 1.0.8
 */

class Document extends Core\Controller {

    private $requestMethod;
    private $param;
    private $value;
    private $key;
    protected $Document = null;
    protected $View = null;
    

    public function __construct($requestMethod = '', $key = '', $value = '', $param = []) {
        // Create a new instance of the model document class.
        $this->Document = Model\Document::getInstance();
        // Create a new instance of the model shipment class.
        $this->Shipment = Model\Shipment::getInstance();
        // Create a new instance of the core view class.
        $this->View = new Core\View;

        $this->requestMethod = $requestMethod;
        $this->param = $param;
        $this->value = $value;
        $this->key = $key;
    }

    /**
     * Document Index: Renders the document view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function index($shipment_id = "", $type = "", $user_id = "") {

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

        if (!$Role = Model\Role::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($userID);

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        $selectedTheme = $User->getUserSettings($user_id);
        if(isset($selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = '';
        }
        
        $this->View->addJS("js/document.js");
        $this->View->addCSS("css/document.css");
        
        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        //$this->View->addCSS("css/".$selectedTheme.".css");

        $this->View->render("/document/index", [
            "title" => "Shipment API",
            "id" => $User->data()->id,
            "email" => $User->data()->email,
            "shipment" => ["shipment_id" => $shipment_id, "type" => $type], 
            "document" => $this->Document->getDocumentByShipment($shipment_id, $type),
            "user_settings" => $User->getUserSettings($user_id)
        ]);
    }

    public function processDocument() {
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

    // API Get document by document_id
    private function getDocumentByDocID($document_id, $param) {
        $args = (isset($param[6]) && !empty($param[6])) ? $param[6] : "*";
        $result = $this->Document->getDocumentByDocID($document_id, $args);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    // API Get document by shipment_id
    private function getDocumentByShipID($shipment_id, $param) {
        $args = (isset($param[6]) && !empty($param[6])) ? $param[6] : "*";
        $result = $this->Document->getDocumentByShipID($shipment_id, $args);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    // API Get document by user_id
    private function getDocumentByUserID($user_id, $param) {
        $args = (isset($param[6]) && !empty($param[6])) ? $param[6] : "*";
        $result = $this->Document->getDocumentByUserID($user_id, $args);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    // API Put document by shipment_id
    private function putDocumentByShipment($shipment_num, $type, $fileName) {

        $document = $this->Shipment->getShipmentByShipID($shipment_num);

        $data = [];
        $data['shipment_id'] = $document[0]->id;
        $data['shipment_num'] = $document[0]->shipment_num;
        $data['type'] = $type;
        $data['name'] = $fileName;
        $data['saved_by'] = "";
        $data['saved_date'] = "";
        $data['event_date'] = "";
        $data['path'] = "";
        $data['upload_src'] = "hub";

        $result = $this->Document->putDocument($data);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;

    }

    // API Delete document by document_id
    private function deleteDocumentByDocID($document_id) {
        $result = $this->Document->deleteDocumentByDocID($document_id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
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
    private function uploadDocument($param) {

        $User = Model\User::getInstance($param[5]);
        $email = $User->data()->email;
        $shipment_num = $param[6];
        $type = $param[7];
        $domain = "http://a2bfreighthub.com";
        $physical_path = "E:/A2BFREIGHT_MANAGER";

        $preview = $config = $errors = [];
        $input = 'input'; // the input name for the fileinput plugin
        if (empty($_FILES[$input])) {
            return [];
        }
        $total = count($_FILES[$input]['name']); // multiple files
        // $path = './uploads/'; // your upload path
        $path = $physical_path . '/' . $email . '/CW_FILE/' . $shipment_num . '/';

        for ($i = 0; $i < $total; $i++) {
            $tmpFilePath = $_FILES[$input]['tmp_name'][$i]; // the temp file path
            $fileName = $_FILES[$input]['name'][$i]; // the file name
            $fileSize = $_FILES[$input]['size'][$i]; // the file size
            $fileType = $_FILES[$input]['type'][$i]; 
            $fileExtn = pathinfo($fileName, PATHINFO_EXTENSION);

            //Make sure we have a file path
            if ($tmpFilePath != ""){

                //Checks if type is empty
                if(empty($type)) {
                    // Check the document type using python API
                    $obj_type = self::checkDocumentType($fileName, $tmpFilePath);
                    $type = strtoupper($obj_type->files[0]->type);

                    // $endpoint = 'http://a2bfreighthub.com/TEST_API/view.php?file=' . $tmpFilePath;
                    // // $text = file_get_contents($endpoint);
                    // // alternative for file_get_contents
                    // $curlopts = [
                    //   CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
                    // ];
                    // $retval = $this->http_get_contents($endpoint, $curlopts);

                    // $type = "OTHER";
                    // $keywords = array("CIV"=> array("COMMERCIAL INVOICE"), 
                    //                   "BOL"=> array("BILL/LADING NUMBER"), 
                    //                   "ARN"=> array("ARRIVAL NOTICE"), 
                    //                   "MBL"=> array("SEA WAYBILL"), 
                    //                   "PKL"=> array("PACKING LIST")
                    //                 );
                    // foreach ($keywords as $key => $value) {
                    //     foreach ($value as $key2 => $value2) {
                    //         if(strval(strpos(strtoupper($retval),$value2)) != ""){
                    //             $type = $key;
                    //         } 
                    //     }
                    // }          
                }

                //Setup our new file path
                $newFilePath = $path . "/" . $type . "/" . $fileName;
                $newFileUrl = $domain . "/filemanager/" . $email . "/CW_FILE/" . $shipment_num . "/" . $type . "/" . $fileName;

                // On the other hand, 'is_dir' is a bit faster than 'file_exists'.
                if (!is_dir($path . "/" . $type . "/")) {
                    // @mkdir($path);
                    mkdir($path . "/" . $type . "/", 0777, true);
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
                        'type' => $fileExtn,
                        'fileType' => $type
                    ];
                    
                    // save to database
                    $this->putDocumentByShipment($shipment_num, $type, $fileName);
                } else {
                    $errors[] = $fileName;
                }
            } else {
                $errors[] = $fileName;
            }
            //initialize type to empty
            $type="";
        }
        // push to cargowise
        // User setting (tick box) 
        $user_settings = $User->getUserSettings($param[5]);
        $document_settings = json_decode($user_settings[0]->document);
        if(isset($document_settings->doctracker->auto_push)) {
            self::uploadToCargoWise($shipment_num, $email, $config);
        }
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

    /**
     * Check Document Type: Check document type
     * returns object
     * @access private
     * @param string $sql
     * @param array $params
     * @return object
     * @since 1.10.1
     */
    private function checkDocumentType($filename, $file) {
        $url = 'http://a2bfreighthub.com:5000/classify';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, [
            'multipart' => [
                [ 'name' => 'client', 'contents' => 'a2b'],
                [
                    'Content-type' => 'multipart/form-data',
                    'name' => 'file',
                    'contents' => Psr7\Utils::tryFopen($file, 'r'),
                    'filename' => $filename,

                ]
            ],
        ]);

        return json_decode($response->getBody());
    }


    /**
     * Upload To Cargowise.
     * @access public
     * @since 1.0.2
     */
    public static function uploadToCargoWise($shipment_num = "", $email, $files){

        $postfield = '<UniversalEvent xmlns="http://www.cargowise.com/Schemas/Universal/2011/11" version="1.1">
            <Event>
                <DataContext>
                    <DataTargetCollection>
                        <DataTarget>
                            <Type>ForwardingShipment</Type>
                            <Key>' . $shipment_num . '</Key>
                        </DataTarget>
                    </DataTargetCollection>
                    <Company>
                        <Code>SYD</Code>
                    </Company>
                    <EnterpriseID>A2B</EnterpriseID>
                    <ServerID>TRN</ServerID>
                </DataContext>
                <EventTime>2020-11-11T21:32:25.647</EventTime>
                <EventType>DIM</EventType>
                <IsEstimate>false</IsEstimate>
                <AttachedDocumentCollection>';
                foreach ($files as $key => $value) {
                    $file = "E:/A2BFREIGHT_MANAGER/".$email."/CW_FILE/".$shipment_num."/".$value['fileType']."/" . $value['caption'];    
                    $imgData = file_get_contents($file);
                    $base64 = base64_encode($imgData);
        
                    $postfield .= "<AttachedDocument>
                        <FileName> " . $value['caption'] . "</FileName>
                        <ImageData>" . $base64 . "</ImageData>
                        <Type>
                            <Code>" . $value['fileType'] . "</Code>
                            <Description></Description>
                        </Type>
                        <IsPublished>true</IsPublished>
                    </AttachedDocument>";
                }
        $postfield .= '</AttachedDocumentCollection>
            </Event>
        </UniversalEvent>';

        $curl = curl_init();
 
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://a2btrnservices.wisegrid.net/eAdaptor",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postfield,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/xml',
                'Authorization: Basic QTJCOkh3N20zWGhT',
                'Cookie: WEBSVC=109af0692bd5564a'
            ),
        ));

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        // echo $response;
    }

    /**
     * Push To Cargowise. Same with uploadToCargowise
     * @access public
     * @since 1.0.2
     */
    public function pushToCargoWise(){

        if(!isset($_POST['user_id']) && !isset($_POST['doc_id']) ) {
            echo "Access Denied";
            exit;
        }

        $user_id = $_POST['user_id'];
        $document_id = $_POST['doc_id'];

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

        $document = $this->Document->getDocumentByDocID($document_id);
        $email = $User->data()->email; 
        $shipment_num = $document[0]->shipment_num;
        $document_name = $document[0]->name;
        $document_type = $document[0]->type;

        $file = "E:/A2BFREIGHT_MANAGER/".$email."/CW_FILE/".$shipment_num."/".$document_type."/" . $document_name;    
        $imgData = file_get_contents($file);
        $base64 = base64_encode($imgData);

        $postfield = '<UniversalEvent xmlns="http://www.cargowise.com/Schemas/Universal/2011/11" version="1.1">
            <Event>
                <DataContext>
                    <DataTargetCollection>
                        <DataTarget>
                            <Type>ForwardingShipment</Type>
                            <Key>' . $shipment_num . '</Key>
                        </DataTarget>
                    </DataTargetCollection>
                    <Company>
                        <Code>SYD</Code>
                    </Company>
                    <EnterpriseID>A2B</EnterpriseID>
                    <ServerID>TRN</ServerID>
                </DataContext>
                <EventTime>2020-11-11T21:32:25.647</EventTime>
                <EventType>DIM</EventType>
                <IsEstimate>false</IsEstimate>
                <AttachedDocumentCollection>
                    <AttachedDocument>
                        <FileName> ' . $document_name . '</FileName>
                        <ImageData>' . $base64 . '</ImageData>
                        <Type>
                            <Code>' . $document_type . '</Code>
                            <Description></Description>
                        </Type>
                        <IsPublished>true</IsPublished>
                    </AttachedDocument>
                </AttachedDocumentCollection>
            </Event>
        </UniversalEvent>';

        $curl = curl_init();
 
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://a2btrnservices.wisegrid.net/eAdaptor",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postfield,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/xml',
                'Authorization: Basic QTJCOkh3N20zWGhT',
                'Cookie: WEBSVC=109af0692bd5564a'
            ),
        ));

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        // echo $response;
    }
	
	public function getCurlValue($filename, $contentType, $postname) {
        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        if (function_exists('curl_file_create')) {
            return curl_file_create($filename, $contentType, $postname);
        }

        // Use the old style if using an older version of PHP
        $value = "@{$filename};filename=" . $postname;
        if ($contentType) {
            $value .= ';type=' . $contentType;
        }

        return $value;
    }

    /**
     * Upload: Upload the bootstrap-fileinput files in chunk
     * returns associative array
     * @access public
     * 
     * @return array
     * @since 1.0.8
     */
    private function uploadChunk() {
        $preview = $config = $errors = [];
        $targetDir = './uploads/';
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }
        $fileBlob = 'fileBlob';                      // the parameter name that stores the file blob
        if (isset($_FILES[$fileBlob]) && isset($_POST['uploadToken'])) {
            $token = $_POST['uploadToken'];          // gets the upload token
            if (!validateToken($token)) {            // your access validation routine (not included)
                return [
                    'error' => 'Access not allowed'  // return access control error
                ];
            }
            $file = $_FILES[$fileBlob]['tmp_name'];  // the path for the uploaded file chunk 
            $fileName = $_POST['fileName'];          // you receive the file name as a separate post data
            $fileSize = $_POST['fileSize'];          // you receive the file size as a separate post data
            $fileId = $_POST['fileId'];              // you receive the file identifier as a separate post data
            $index =  $_POST['chunkIndex'];          // the current file chunk index
            $totalChunks = $_POST['chunkCount'];     // the total number of chunks for this file
            $targetFile = $targetDir.'/'.$fileName;  // your target file path
            if ($totalChunks > 1) {                  // create chunk files only if chunks are greater than 1
                $targetFile .= '_' . str_pad($index, 4, '0', STR_PAD_LEFT); 
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
                    $this->combineChunks($chunks, $outFile);
                } 
                // if you wish to generate a thumbnail image for the file
                $targetUrl = $this->getThumbnailUrl($path, $fileName);
                // separate link for the full blown image file
                $zoomUrl = './uploads/' . $fileName;
                return [
                    'chunkIndex' => $index,         // the chunk index processed
                    'initialPreview' => $targetUrl, // the thumbnail preview data (e.g. image)
                    'initialPreviewConfig' => [
                        [
                            'type' => 'image',      // check previewTypes (set it to 'other' if you want no content preview)
                            'caption' => $fileName, // caption
                            'key' => $fileId,       // keys for deleting/reorganizing preview
                            'fileId' => $fileId,    // file identifier
                            'size' => $fileSize,    // file size
                            'zoomData' => $zoomUrl, // separate larger zoom data
                        ]
                    ],
                    'append' => true
                ];
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
    private function combineChunks($chunks, $targetFile) {
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
    private function getThumbnailUrl($path, $fileName) {
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
            return 'http://localhost/uploads/thumbs/' . $fileName;
        } else {
            return 'http://localhost/uploads/' . $fileName; // return the original file
        }
    }

    public function updateDocumentStatus(){
        echo json_encode($this->Document->updateDocumentStatus($_POST));
    }

    public function updateDocumentBulk(){
        echo json_encode($this->Document->updateDocumentBulk($_POST));
    }

    public function fileviewer($user_id = "", $document_id){ 

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

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        if (!$Role = Model\Role::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user_id)->role_name;

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        $this->View->renderWithoutHeaderAndFooter("/document/fileviewer", [
            "email" => $User->data()->email,
            "document_id" => $document_id,
        ]);
    }

    public function comment($document_id, $param = "", $user_id = ""){ 

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

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        if (!$Role = Model\Role::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user_id)->role_name;

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        // Comment view
        switch ($param) {
            case 'view':
                $results = $this->Document->getDocumentComment($document_id);
                $document_status = "";
                break;
            
            default:
                $results = "";
                $document_status = $param;
                break;
        }

        $this->View->addJS("js/document.js");
        $this->View->addCSS("css/document.css");

        $this->View->render("/document/comment", [
            'view' => $param,
            'user_id' => $user_id,
            'document_id' => $document_id,
            'document_status' => $document_status,
            'results' => $results
        ]);
    }

    public function request($shipment_num, $document = "", $user_id = ""){ 

        /* ==============================================
        // NOTE: shipment_id and shipment_num are diff
        // shipment_id : XXXX
        // shipment_num : S00000XX
        ============================================== */

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

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        if (!$Role = Model\Role::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user_id)->role_name;

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        // Comment view (check if edit or request)
        // switch ($param) {
        //     case 'edit':
        //         $document_id = $document;
        //         
        //         $document_status = "";
        //         break;
            
        //     default:
        //         $results = "";
        //         $document_status = $param;
        //         break;
        // }

        // Comment view (check if edit or request)
        // New implimentation, check if document (id or type)
        $shipment = [];
        $results = [];
        if(is_numeric($document)) {
            $results = $this->Document->getDocumentByDocID($document);

        } 

        $shipment = $this->Shipment->getShipmentByShipID($shipment_num);
        $shipment_id = $shipment[0]->id;

        // $emailList = $this->Shipment->getShipmentThatHasUser($user_id);
        $emailList = Model\Shipment::getContactEmailByShipmentID($shipment_id);

        $this->View->addJS("js/document.js");
        $this->View->addCSS("css/document.css");

        $this->View->renderWithoutHeaderAndFooter("/document/request", [
            'user_id' => $user_id,
            'document' => $document,
            'shipment_num' => $shipment_num,
            'shipment' => $shipment,
            'results' => $results,
            'emailList' => $emailList
        ]);
    }

    public function putDocumentComment() {
        echo json_encode($this->Document->putDocumentComment($_POST));
    }

    public function putDocumentRequest() {

        $_POST['token'] = $this->generateToken();
        echo json_encode($this->Document->putDocumentRequest($_POST));
    }

    private function generateToken($limit = 16) {
        //Generate a random string.
        $token = openssl_random_pseudo_bytes($limit);

        //Convert the binary data into hexadecimal representation.
        $token = bin2hex($token);

        return $token;
    }

    // alternative for file_get_contents
    private function http_get_contents($url, Array $opts = []) {
        $ch = curl_init();
        if(!isset($opts[CURLOPT_TIMEOUT])) {
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        if(is_array($opts) && $opts) {
            foreach($opts as $key => $val) {
                curl_setopt($ch, $key, $val);
            }
        }
        if(!isset($opts[CURLOPT_USERAGENT])) {
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['SERVER_NAME']);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if(FALSE === ($retval = curl_exec($ch))) {
            error_log(curl_error($ch));
        }
        return $retval;
    }

        /**
     * Upload: Upload the bootstrap-fileinput files
     * returns associative array
     * @access private
     * 
     * @return array
     * @since 1.0.8
     */
    private function deleteDocument() {
        $document_id = $_POST['key'];
        $result = $this->deleteDocumentByDocID($document_id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

}