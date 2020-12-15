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
    public function post($collection, $key, $value) { 

        $this->header();

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );

        // Checking the request method
        $requestMethod = self::checkRequestMethod($uri[2]);

        // All of our endpoints start with /get
        // everything else results in a 404 Not Found
        if (!isset($collection) || empty($collection)) {
            // header("HTTP/1.1 404 Not Found");
            Utility\Redirect::to('/api');
            exit();
        }

        // Second parameter can be any id (user, shipment, document) and
        // also check if id is a user id
        // $param = null;
        // if (isset($key)) {
        //     $param = !is_numeric($key) ? $key : (int) $key;
        // }

        // Pass the request method, user id and extra arg to the specific controller and process the HTTP request:
        // $controller = new ProcessController($requestMethod, $param);
        // $controller->processRequest();

        switch ($collection) { // Processing collection
            case 'shipment': // Ex. 3 (user_id)
                $shipment = new Shipment($requestMethod, $key, $value, $uri);
                $results = $shipment->processShipment();
                break;
            case 'document': // Ex. S00001055 (shipment_id)
                $document = new Document($requestMethod, $key, $value , $uri);
                $results = $document->processDocument();
                break;

            default:
                $message = "Unable to fetch request.";
                echo json_encode(array("message" => $message)); exit;
                break;
        }

    } 

    // READ
    public function get($collection, $key, $value) { 

        $this->header();

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );

        // Sanitize the request method
        $requestMethod = self::checkRequestMethod($uri[2]);

        // All of our endpoints start with /get
        // everything else results in a 404 Not Found
        if (!isset($collection) || empty($collection)) {
            // header("HTTP/1.1 404 Not Found");
            Utility\Redirect::to('/api');
            exit();
        }

        // REQUESTMETHOD/COLLECTION/KEY/VALUE
        // get/document/sid/S00000001

        // Key can be any id (user, shipment, document) and
        // also check if id is a user id
        // $key = null; $value = null;
        // if (isset($key) && isset($value)) {
        //     $key = !is_numeric($key) ? $key : (int) $key;
        //     $value = !is_numeric($value) ? $value : (int) $value;
        // }

        // Pass the request method, user id and extra arg to the specific controller and process the HTTP request:
        // $controller = new ProcessController($requestMethod, $param);
        // $controller->processRequest();

        switch ($collection) { // Processing collection
            case 'shipment': // Ex. 3 (user_id)
                $shipment = new Shipment($requestMethod, $key, $value, $uri);
                $results = $shipment->processShipment();
                break;
            case 'document': // Ex. S00001055 (shipment_id)
                $document = new Document($requestMethod, $key, $value , $uri);
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