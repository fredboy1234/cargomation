<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

/**
 * API Controller:
 *
 * @author John Alex
 * @since 1.0.8
 */

class Api extends Core\Controller {

    public function index() {
        echo "Invalid Request";
        // Utility\Redirect::to('404.html');
    }

    private function header() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

    public function checkRequestMethod($uri) {
        if($_SERVER['REQUEST_METHOD'] == strtoupper($uri)) {
            $requestMethod = $_SERVER['REQUEST_METHOD'];
        } else {
            echo "Invalid Request Method";
            exit;            
        }

        return $requestMethod;
    }

    // CREATE
    public function post() { } 

    // READ
    public function get() { 

        $this->header();

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );

        // Checking the request method
        $requestMethod = self::checkRequestMethod($uri[2]);

        // All of our endpoints start with /get
        // everything else results in a 404 Not Found
        if (!isset($uri[3]) || empty($uri[3])) {
            // header("HTTP/1.1 404 Not Found");
            Utility\Redirect::to('/api');
            exit();
        }

        // Second parameter can be any id (user, shipment, document) and
        // also check if id is a user id
        $param = null;
        if (isset($uri[4])) {
            $param = !is_numeric($uri[4]) ? $uri[4] : (int) $uri[4];
        }

        // Pass the request method, user id and extra arg to the specific controller and process the HTTP request:
        // $controller = new ProcessController($requestMethod, $param);
        // $controller->processRequest();

        switch ($uri[3]) { // Processing collection
            case 'shipment': // Ex. 3 (user_id)
                $shipment = new Shipment($requestMethod, $param);
                $results = $shipment->processShipment();
                break;
            case 'document': // Ex. S00001055 (shipment_id)
                $document = new Document($requestMethod, $param);
                $results = $document->processDocument();
                break;

            default:
                $message = "Unable to fetch request.";
                echo json_encode(array("message" => $message)); exit;
                break;
        }
    }

    // UPDATE
    public function put() { } 

    // DELETE
    public function delete() { } 

}