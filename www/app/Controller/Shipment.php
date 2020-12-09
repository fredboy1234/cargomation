<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;

/**
 * API Controller:
 *
 * @author John Alex
 * @since 1.0
 */

class Shipment extends Core\Controller {

    private $requestMethod;
    private $user_id;
    private $shipment;

    public function __construct($requestMethod, $user_id = "", $shipment = " ") {
        $this->user_id = $user_id;
        $this->shipment = $shipment;
        $this->requestMethod = $requestMethod;
        $this->shipment = Model\Shipment::getInstance();
    }

    public function processShipment() {
        // Processing request.. 
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->user_id) {
                    $response = $this->getShipment($this->user_id);
                } else {
                    $response = $this->getAllShipment();
                };
                break;
            case 'POST':
                // $response = $this->createShipmentFromRequest();
                // break;
            case 'PUT':
                // $response = $this->updateShipmentFromRequest($this->userId);
                // break;
            case 'DELETE':
                // $response = $this->deleteShipment($this->userId);
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

    private function getShipment($user_id) {
        $result = $this->shipment->getShipment($user_id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;

    }

    private function getAllShipment() {
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