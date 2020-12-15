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

    public static function getDocument($document_id, $arg) {

        $column = "*";
        if(isset($arg[6]) || !empty($arg[6])) {
            $column = $arg[6];
        }

        if(is_array($document_id)) {
            $document_id = implode("','", array_column($document_id, 'shipment_num'));
        }

        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT " . $column . "
                                FROM document 
                                LEFT JOIN document_status ON document.id = document_status.document_id
                                WHERE document.id IN ('" . $document_id . "') ")->results();
    }

    public static function putDocument($data) {

        // Sanitize array and implode
        if(is_array($data)) {
            $value = implode("','", array_values($data));
            $column = implode(", ", array_keys($data));
        }

        $Db = Utility\Database::getInstance();
        // echo "INSERT INTO document (" . $column . ") VALUES ('" . $value . "')";
        $current_id = $Db->query("INSERT INTO document (shipment_id, shipment_num, type, name, saved_by, saved_date, event_date, path, upload_src)
        VALUES ('" . $data['shipment_id'] . "','" . $data['shipment_num'] . "','" . $data['type'] . "','" . $data['name'] . "', '', getdate(), getdate(),'','" . $data['upload_src'] . "'); 
        SELECT SCOPE_IDENTITY()");

        return $Db->query("INSERT INT document_status (document_id, status) VALUES ('" . $current_id . "', 'pending')");
    }

    public static function getDocumentByShipment($shipment_id, $type = "") {

        if(is_array($shipment_id)) {
            $shipment_id = implode("','", array_column($shipment_id, 'shipment_num'));
        }

        if (!empty($type)) {
            $type = "AND type = '{$type}'";
        }

        $Db = Utility\Database::getInstance();
        return $Db->query("SELECT * 
                                FROM document 
                                LEFT JOIN document_status ON document.id = document_status.document_id
                                WHERE shipment_num IN ('" . $shipment_id . "') " . $type)->results();
    }

    public static function addDocumentStatus($data){
        $Db = Utility\Database::getInstance();
        return $Db->query("if exists(select * from document_status where document_id='{$data['doc_id']}')
                            update document_status set status='{$data['doc_status']}' where document_id='{$data['doc_id']}'
                           else
                            insert into document_status (document_id,status) values('{$data['doc_id']}','{$data['doc_status']}')")->results();
    }



}


