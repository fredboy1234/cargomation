<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

use App\Core\View;

/**
 * API Controller:
 *
 * @author John Alex
 * @since 1.0
 */

class Api extends Core\Controller {

    public function index() {
        Utility\Redirect::to('404.html');
    }

    private function header() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

    public function checkRequestMethod($uri = "") {
        if(isset($_SERVER['REQUEST_METHOD']) == $uri) {
            $requestMethod = $_SERVER['REQUEST_METHOD'];
        } else {
            echo "Invalid Request Method";
            exit;            
        }

        return $requestMethod;
    }

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
            Utility\Redirect::to('/404.html');
            exit();
        }

        // Second parameter can be any id (user, shipment, document) and
        // also check if id is a user id
        $param = null;
        if (isset($uri[4])) {
                $param = !is_numeric($uri[4]) ? $uri[4] : (int) $uri[4];
        }

        // Pass the request method, user id and extra arg to the specific controller and process the HTTP request:
        // $controller = new ProcessController($requestMethod, $userId);
        // $controller->processRequest();

        switch ($uri[3]) {
            case 'shipment':
                $shipment = new Shipment($requestMethod, $param);
                $results = $shipment->processShipment();
                break;
            case 'document': //S00001055
                    $document = new Document($requestMethod, $param);
                    $results = $document->processDocument();
                    break;

            default:
                $message = "Unable to fetch request.";
                echo json_encode(array("message" => $message)); exit;
                break;
        }
    }

    public function post() { }
    public function put() { }
    public function delete() { }

}