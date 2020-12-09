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
    private $shipment_id;

    public function __construct($requestMethod, $shipment_id = "") {
        $this->shipment_id = $shipment_id;
        $this->requestMethod = $requestMethod;
        $this->document = Model\Document::getInstance();
    }

    public function processDocument() {
        // Processing request.. 
        switch ($this->requestMethod) {
            case 'POST':
                // $response = $this->createDocumentFromRequest();
                // break;
            case 'GET':
                if ($this->shipment_id) {
                    $response = $this->getDocument($this->shipment_id);
                } else {
                    $response = $this->getAllDocument();
                };
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

    private function getDocument($shipment_id) {
        $result = $this->document->getDocument($shipment_id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;

    }

    private function getAllDocument() {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode("Stop! Higher clearance is needed to access this data.");
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
}