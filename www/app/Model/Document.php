<?php

namespace App\Model;

use Exception;
use App\Core;
use App\Utility;

/**
 * Document Model:
 *
 * @author John Alex
 * @since 1.0.7
 */
class Document extends Core\Model {

    /** @var Database */
    private static $_Document = null;

    public static function getInstance() {
        if (!isset(self::$_Document)) {
            self::$_Document = new Document();
        }
        return(self::$_Document);
    }

    public static function getDocument($shipment_id, $type = "") {

        if(is_array($shipment_id)) {
            $shipment_id = implode("','", array_column($shipment_id, 'shipment_num'));
        }

        if (!empty($type)) {
            $type = "AND type = '{$type}'";
        }

        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT * 
                                FROM document 
                                WHERE shipment_num IN ('" . $shipment_id . "') " . $type)->results();
    }
}


