<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;

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

    public function __construct($requestMethod, $key, $value, $param = []) {
        $this->document = Model\Document::getInstance();
        $this->requestMethod = $requestMethod;
        $this->param = $param;
        $this->value = $value;
        $this->key = $key;
    }

    public function processDocument() {
        // Processing request.. 
        switch (strtoupper($this->requestMethod)) {
            case 'POST': 
                switch ($this->param) {
                    case 'upload':
                        $response = $this->upload();
                        break;
                    case 'uploadchunk':
                        $response = $this->uploadChunk();
                        break;
                    default:
                        # code...
                        break;
                }
                break;
            case 'GET':
                switch ($this->key) {
                    case 'sid': 
                        $response = $this->getDocumentByShipment($this->value);
                        break;
                    case 'did':
                        $response = $this->getDocument($this->value, $this->param);
                        break;
                    case 'all':
                        $response = $this->getAllDocument();
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

    // Get document by document_id
    private function getDocument($document_id, $param) {
        $result = $this->document->getDocument($document_id, $param);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;

    }

    // Get document by shipment_id
    private function getDocumentByShipment($param) {
        $result = $this->document->getDocumentByShipment($param);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;

    }

    private function getAllDocument() {
        $this->unauthorizedAccess();
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
    private function upload() {
        $preview = $config = $errors = [];
        $input = 'input'; // the input name for the fileinput plugin
        if (empty($_FILES[$input])) {
            return [];
        }
        $total = count($_FILES[$input]['name']); // multiple files
        $path = './uploads/'; // your upload path
        for ($i = 0; $i < $total; $i++) {
            $tmpFilePath = $_FILES[$input]['tmp_name'][$i]; // the temp file path
            $fileName = $_FILES[$input]['name'][$i]; // the file name
            $fileSize = $_FILES[$input]['size'][$i]; // the file size
            $fileType = $_FILES[$input]['type'][$i]; 
            $fileExtn = pathinfo($fileName, PATHINFO_EXTENSION);
            
            //Make sure we have a file path
            if ($tmpFilePath != ""){
                //Setup our new file path
                $newFilePath = $path . $fileName;
                $newFileUrl = 'http://basin.con/uploads/' . $fileName;
                
                //Upload the file into the new path
                if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $fileId = $fileName . $i; // some unique key to identify the file
                    $preview[] = $newFileUrl;
                    $config[] = [
                        'key' => $fileId,
                        'caption' => $fileName,
                        'size' => $fileSize,
                        'downloadUrl' => $newFileUrl, // the url to download the file
                        'url' => 'http://superhero.con/delete.php', // server api to delete the file based on key
                        'type' => $fileExtn
                    ];
                } else {
                    $errors[] = $fileName;
                }
            } else {
                $errors[] = $fileName;
            }
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
}