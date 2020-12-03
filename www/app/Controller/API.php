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

class API extends Core\Controller {

    public $shipment_id;

    public function index() {
        $this->View->renderTemplate("admin", "admin/index", [
            "title" => "API"
        ]);
    }

    public function shipment ($shipment_id = "") {

        $api_url = "http://a2bfreighthub.com/eAdaptor/jsoneAdaptor.php?shipment_id=" . $shipment_id;

        $this->View->render("api/shipment", [
            "title" => "Shipment API",
            "shipment" => file_get_contents($api_url)
        ]);
    }

    public function document ($shipment_id = "") {

        $api_url = "http://a2bfreighthub.com/eAdaptor/jsoneAdaptor.php?shipment_id=" . $shipment_id;

        $this->View->render("api/shipment", [
            "title" => "Shipment API",
            "shipment" => file_get_contents($api_url)
        ]);
    }

}