<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;

/**
 * Shipment Controller:
 *
 * @author John Alex
 * @since 1.0.8
 */

class Shipment extends Core\Controller {

    private $requestMethod;
    private $param;
    private $value;
    private $key;

    public function __construct($requestMethod, $key, $value, $param = []) {
        $this->shipment = Model\Shipment::getInstance();
        $this->requestMethod = $requestMethod;
        $this->param = $param;
        $this->value = $value;
        $this->key = $key;
    }

    public function processShipment() {
        // Processing request.. 
        switch ($this->requestMethod) {
            case 'POST':
                // $response = $this->createShipmentFromRequest();
                // break;
            case 'GET':
                switch ($this->key) {
                    case 'sid': 
                        $response = $this->getShipment($this->value);
                        break;
                    case 'did':
                        # code... 
                        break;
                    case 'all':
                        $response = $this->getAllShipment();
                        break;
                    default:
                        $response = $this->unprocessableEntityResponse();
                        break;
                }
                break;
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
}